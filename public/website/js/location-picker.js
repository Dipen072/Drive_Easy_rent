/**
 * DriveEase Real-Time Location & Address Picker (Uber / Ola / Rapido Style)
 * Powered by Leaflet.js, OpenStreetMap Nominatim, & Photon Autocomplete
 */

(function () {
    'use strict';

    // Global location picker helper
    window.DriveEaseLocationPicker = {
        map: null,
        marker: null,
        activeTarget: null, // { addressInput, latInput, lngInput }
        defaultLat: 19.0760, // Default to Mumbai
        defaultLng: 72.8777,

        init: function () {
            this.injectStyles();
            this.createMapModal();
        },

        injectStyles: function () {
            if (document.getElementById('de-location-styles')) return;
            const style = document.createElement('style');
            style.id = 'de-location-styles';
            style.textContent = `
                .de-autocomplete-wrapper {
                    position: relative;
                }
                .de-autocomplete-results {
                    position: absolute;
                    top: 100%;
                    left: 0;
                    right: 0;
                    z-index: 1050;
                    background: #ffffff;
                    border: 1px solid rgba(0,0,0,0.125);
                    border-radius: 0.5rem;
                    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
                    max-height: 280px;
                    overflow-y: auto;
                    margin-top: 4px;
                    display: none;
                }
                .de-autocomplete-item {
                    padding: 10px 14px;
                    cursor: pointer;
                    display: flex;
                    align-items: flex-start;
                    gap: 10px;
                    border-bottom: 1px solid #f1f5f9;
                    transition: background 0.15s ease;
                }
                .de-autocomplete-item:hover, .de-autocomplete-item.active {
                    background: #f8fafc;
                }
                .de-autocomplete-item:last-child {
                    border-bottom: none;
                }
                .de-autocomplete-icon {
                    color: #0d6efd;
                    font-size: 1rem;
                    margin-top: 3px;
                }
                .de-autocomplete-title {
                    font-weight: 600;
                    font-size: 0.9rem;
                    color: #1e293b;
                }
                .de-autocomplete-sub {
                    font-size: 0.78rem;
                    color: #64748b;
                }
                #deMapContainer {
                    width: 100%;
                    height: 380px;
                    border-radius: 0.5rem;
                }
            `;
            document.head.appendChild(style);
        },

        createMapModal: function () {
            if (document.getElementById('deMapModal')) return;

            const modalHtml = `
                <div class="modal fade" id="deMapModal" tabindex="-1" aria-labelledby="deMapModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title font-heading fw-700" id="deMapModalLabel">
                                    <i class="fas fa-map-marked-alt text-primary me-2"></i>Pin Drag & Drop Location
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="text-muted small mb-2">Drag the pin marker or click anywhere on the map to pinpoint exact location.</p>
                                <div id="deMapContainer"></div>
                                <div class="mt-3 p-3 bg-light rounded d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="small fw-bold text-secondary">Selected Address:</div>
                                        <div id="deMapSelectedAddress" class="fw-600 text-dark small">Fetching address...</div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="deMapGpsBtn">
                                        <i class="fas fa-crosshairs me-1"></i>My Location
                                    </button>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary btn-sm fw-600 px-4" id="deConfirmMapLocation">
                                    <i class="fas fa-check me-1"></i>Confirm Location
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modalHtml);

            document.getElementById('deConfirmMapLocation').addEventListener('click', () => {
                if (this.activeTarget && this.tempAddress) {
                    this.activeTarget.addressInput.value = this.tempAddress;
                    if (this.activeTarget.latInput) this.activeTarget.latInput.value = this.tempLat;
                    if (this.activeTarget.lngInput) this.activeTarget.lngInput.value = this.tempLng;
                }
                const modalEl = document.getElementById('deMapModal');
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) modalInstance.hide();
            });

            document.getElementById('deMapGpsBtn').addEventListener('click', () => {
                this.getCurrentGPSLocation((lat, lng, address) => {
                    this.updateMapPosition(lat, lng, address);
                });
            });
        },

        setupAutocomplete: function (addressInputId, latInputId, lngInputId, mapBtnId, gpsBtnId) {
            const addressInput = document.getElementById(addressInputId);
            if (!addressInput) return;

            const latInput = latInputId ? document.getElementById(latInputId) : null;
            const lngInput = lngInputId ? document.getElementById(lngInputId) : null;
            const mapBtn   = mapBtnId ? document.getElementById(mapBtnId) : null;
            const gpsBtn   = gpsBtnId ? document.getElementById(gpsBtnId) : null;

            // Wrap input if not already wrapped
            let wrapper = addressInput.closest('.de-autocomplete-wrapper');
            if (!wrapper) {
                wrapper = document.createElement('div');
                wrapper.className = 'de-autocomplete-wrapper';
                addressInput.parentNode.insertBefore(wrapper, addressInput);
                wrapper.appendChild(addressInput);
            }

            // Create results container
            let resultsDiv = wrapper.querySelector('.de-autocomplete-results');
            if (!resultsDiv) {
                resultsDiv = document.createElement('div');
                resultsDiv.className = 'de-autocomplete-results';
                wrapper.appendChild(resultsDiv);
            }

            let debounceTimer = null;

            addressInput.addEventListener('input', (e) => {
                const query = e.target.value.trim();
                clearTimeout(debounceTimer);

                if (query.length < 3) {
                    resultsDiv.style.display = 'none';
                    return;
                }

                debounceTimer = setTimeout(() => {
                    this.fetchPlaces(query, (places) => {
                        this.renderAutocompleteResults(resultsDiv, places, (place) => {
                            addressInput.value = place.formatted;
                            if (latInput) latInput.value = place.lat;
                            if (lngInput) lngInput.value = place.lng;
                            resultsDiv.style.display = 'none';
                        });
                    });
                }, 300);
            });

            document.addEventListener('click', (e) => {
                if (!wrapper.contains(e.target)) {
                    resultsDiv.style.display = 'none';
                }
            });

            if (gpsBtn) {
                gpsBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    gpsBtn.disabled = true;
                    const originalHtml = gpsBtn.innerHTML;
                    gpsBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Locating...';

                    this.getCurrentGPSLocation((lat, lng, address) => {
                        addressInput.value = address;
                        if (latInput) latInput.value = lat;
                        if (lngInput) lngInput.value = lng;
                        gpsBtn.disabled = false;
                        gpsBtn.innerHTML = originalHtml;
                    }, () => {
                        gpsBtn.disabled = false;
                        gpsBtn.innerHTML = originalHtml;
                    });
                });
            }

            if (mapBtn) {
                mapBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.activeTarget = { addressInput, latInput, lngInput };
                    const currentLat = (latInput && latInput.value) ? parseFloat(latInput.value) : this.defaultLat;
                    const currentLng = (lngInput && lngInput.value) ? parseFloat(lngInput.value) : this.defaultLng;
                    const currentAddr = addressInput.value || '';

                    this.openMapModal(currentLat, currentLng, currentAddr);
                });
            }
        },

        fetchPlaces: function (query, callback) {
            // Use Komoot Photon API for fast global autocomplete
            const url = `https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&limit=5`;
            fetch(url)
                .then(res => res.json())
                .then(data => {
                    if (!data.features) {
                        callback([]);
                        return;
                    }
                    const places = data.features.map(f => {
                        const props = f.properties;
                        const coords = f.geometry.coordinates; // [lng, lat]
                        const name = props.name || props.street || '';
                        const parts = [
                            props.district,
                            props.city || props.town || props.village,
                            props.state,
                            props.country
                        ].filter(Boolean);

                        return {
                            name: name,
                            subtext: parts.join(', '),
                            formatted: name ? `${name}, ${parts.join(', ')}` : parts.join(', '),
                            lat: coords[1],
                            lng: coords[0]
                        };
                    });
                    callback(places);
                })
                .catch(err => {
                    console.error('Location fetch error:', err);
                    callback([]);
                });
        },

        renderAutocompleteResults: function (container, places, onSelect) {
            if (!places || places.length === 0) {
                container.innerHTML = `<div class="p-3 text-muted small text-center"><i class="fas fa-exclamation-circle me-1"></i>No addresses found</div>`;
                container.style.display = 'block';
                return;
            }

            container.innerHTML = places.map((p, idx) => `
                <div class="de-autocomplete-item" data-idx="${idx}">
                    <i class="fas fa-map-marker-alt de-autocomplete-icon"></i>
                    <div>
                        <div class="de-autocomplete-title">${p.name || 'Selected Location'}</div>
                        <div class="de-autocomplete-sub">${p.subtext}</div>
                    </div>
                </div>
            `).join('');

            container.style.display = 'block';

            const items = container.querySelectorAll('.de-autocomplete-item');
            items.forEach((item, idx) => {
                item.addEventListener('click', () => {
                    onSelect(places[idx]);
                });
            });
        },

        getCurrentGPSLocation: function (onSuccess, onError) {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser.');
                if (onError) onError();
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    this.reverseGeocode(lat, lng, (address) => {
                        onSuccess(lat, lng, address);
                    });
                },
                (err) => {
                    alert('Unable to retrieve your location. Please ensure location services are enabled.');
                    console.error(err);
                    if (onError) onError();
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        },

        reverseGeocode: function (lat, lng, callback) {
            const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`;
            fetch(url, { headers: { 'User-Agent': 'DriveEase Car Rental App' } })
                .then(res => res.json())
                .then(data => {
                    const address = data.display_name || `${lat.toFixed(5)}, ${lng.toFixed(5)}`;
                    callback(address);
                })
                .catch(err => {
                    callback(`Location (${lat.toFixed(4)}, ${lng.toFixed(4)})`);
                });
        },

        openMapModal: function (lat, lng, initialAddress) {
            const modalEl = document.getElementById('deMapModal');
            const bsModal = new bootstrap.Modal(modalEl);
            bsModal.show();

            modalEl.addEventListener('shown.bs.modal', () => {
                this.initLeafletMap(lat, lng, initialAddress);
            }, { once: true });
        },

        initLeafletMap: function (lat, lng, initialAddress) {
            if (typeof L === 'undefined') {
                console.error('Leaflet JS is not loaded');
                return;
            }

            const mapDiv = document.getElementById('deMapContainer');
            if (this.map) {
                this.map.remove();
                this.map = null;
            }

            this.map = L.map(mapDiv).setView([lat, lng], 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(this.map);

            this.marker = L.marker([lat, lng], { draggable: true }).addTo(this.map);

            this.updateMapPosition(lat, lng, initialAddress);

            this.marker.on('dragend', (e) => {
                const position = e.target.getLatLng();
                this.reverseGeocode(position.lat, position.lng, (address) => {
                    this.updateMapPosition(position.lat, position.lng, address);
                });
            });

            this.map.on('click', (e) => {
                const position = e.latlng;
                this.marker.setLatLng(position);
                this.reverseGeocode(position.lat, position.lng, (address) => {
                    this.updateMapPosition(position.lat, position.lng, address);
                });
            });
        },

        updateMapPosition: function (lat, lng, address) {
            this.tempLat = lat;
            this.tempLng = lng;
            this.tempAddress = address || 'Fetching address...';

            if (this.map && this.marker) {
                this.map.panTo([lat, lng]);
                this.marker.setLatLng([lat, lng]);
            }

            const displayEl = document.getElementById('deMapSelectedAddress');
            if (displayEl) {
                displayEl.textContent = this.tempAddress;
            }

            if (!address) {
                this.reverseGeocode(lat, lng, (addr) => {
                    this.tempAddress = addr;
                    if (displayEl) displayEl.textContent = addr;
                });
            }
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        window.DriveEaseLocationPicker.init();
    });
})();
