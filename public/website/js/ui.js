/**
 * DriveEase — Shared UI Utilities
 * ui.js
 * Toast notifications, modals, skeleton loaders, shared components
 */

// ============================================================
// TOAST NOTIFICATIONS
// ============================================================
const Toast = {
  container: null,

  init() {
    if (!this.container) {
      this.container = document.createElement('div');
      this.container.className = 'toast-container-brand';
      document.body.appendChild(this.container);
    }
  },

  show(type, title, message, duration = 4000) {
    this.init();
    const icons = { success: 'fa-circle-check', error: 'fa-circle-xmark', warning: 'fa-triangle-exclamation', info: 'fa-circle-info' };
    const toast = document.createElement('div');
    toast.className = `toast-brand toast-${type}`;
    toast.innerHTML = `
      <div class="toast-icon"><i class="fas ${icons[type] || icons.info}"></i></div>
      <div class="toast-body">
        <div class="toast-title">${title}</div>
        ${message ? `<div class="toast-message">${message}</div>` : ''}
      </div>
      <button class="toast-close" onclick="this.closest('.toast-brand').remove()"><i class="fas fa-xmark"></i></button>
    `;
    this.container.appendChild(toast);
    setTimeout(() => {
      toast.style.animation = 'fadeIn 0.3s ease reverse';
      setTimeout(() => toast.remove(), 300);
    }, duration);
    return toast;
  },

  success(title, msg, dur)  { return this.show('success', title, msg, dur); },
  error(title, msg, dur)    { return this.show('error',   title, msg, dur); },
  warning(title, msg, dur)  { return this.show('warning', title, msg, dur); },
  info(title, msg, dur)     { return this.show('info',    title, msg, dur); }
};

// ============================================================
// CONFIRMATION MODAL (using Bootstrap modal)
// ============================================================
const Confirm = {
  modal: null,

  init() {
    if (document.getElementById('confirmModal')) return;
    const el = document.createElement('div');
    el.innerHTML = `
      <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
          <div class="modal-content">
            <div class="modal-header border-0 pb-0">
              <div id="confirmIcon" class="d-flex align-items-center justify-content-center rounded-circle mb-1" style="width:52px;height:52px;font-size:1.5rem;"></div>
            </div>
            <div class="modal-body text-center pt-2 pb-1">
              <h5 id="confirmTitle" class="fw-700 mb-2"></h5>
              <p id="confirmMessage" class="text-muted mb-0" style="font-size:.9375rem;"></p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pt-1">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="min-width:90px;">Cancel</button>
              <button type="button" id="confirmOkBtn" class="btn" style="min-width:90px;"></button>
            </div>
          </div>
        </div>
      </div>`;
    document.body.appendChild(el);
    this.modal = new bootstrap.Modal(document.getElementById('confirmModal'));
  },

  show({ title = 'Are you sure?', message = '', okText = 'Confirm', type = 'danger', onConfirm = () => {} }) {
    this.init();
    const colors   = { danger: '#ef4444', warning: '#f59e0b', success: '#10b981', info: '#2563eb' };
    const bgColors = { danger: '#fee2e2', warning: '#fef3c7', success: '#d1fae5', info: '#dbeafe' };
    const icons    = { danger: 'fa-trash-alt', warning: 'fa-exclamation-triangle', success: 'fa-check', info: 'fa-info' };
    const btnCls   = { danger: 'btn-danger', warning: 'btn-warning', success: 'btn-success', info: 'btn-primary' };

    document.getElementById('confirmTitle').textContent   = title;
    document.getElementById('confirmMessage').textContent = message;
    document.getElementById('confirmOkBtn').textContent   = okText;
    document.getElementById('confirmOkBtn').className     = `btn ${btnCls[type]}`;
    const iconEl = document.getElementById('confirmIcon');
    iconEl.style.background = bgColors[type];
    iconEl.style.color      = colors[type];
    iconEl.innerHTML        = `<i class="fas ${icons[type]}"></i>`;

    document.getElementById('confirmOkBtn').onclick = () => {
      this.modal.hide();
      onConfirm();
    };
    this.modal.show();
  }
};

// ============================================================
// SKELETON LOADING HELPERS
// ============================================================
const Skeleton = {
  carCard() {
    return `
      <div class="col">
        <div class="car-card p-0">
          <div class="skeleton skeleton-img"></div>
          <div class="car-body">
            <div class="skeleton skeleton-text skeleton-title mb-3"></div>
            <div class="skeleton skeleton-text" style="width:80%"></div>
            <div class="skeleton skeleton-text" style="width:60%"></div>
            <div class="d-flex gap-2 mt-3">
              <div class="skeleton skeleton-btn" style="width:48%"></div>
              <div class="skeleton skeleton-btn" style="width:48%"></div>
            </div>
          </div>
        </div>
      </div>`;
  },
  renderSkeletons(container, count = 6) {
    $(container).html(Array(count).fill(this.carCard()).join(''));
  }
};

// ============================================================
// STAR RATING RENDERER
// ============================================================
function renderStars(rating, showNumber = true) {
  const full  = Math.floor(rating);
  const half  = rating % 1 >= 0.5;
  const empty = 5 - full - (half ? 1 : 0);
  let stars   = '';
  for (let i = 0; i < full; i++)  stars += '<i class="fas fa-star"></i>';
  if (half)                        stars += '<i class="fas fa-star-half-alt"></i>';
  for (let i = 0; i < empty; i++) stars += '<i class="far fa-star"></i>';
  return `<span class="star-rating">${stars}</span>${showNumber ? ` <span class="rating-text">${rating}</span>` : ''}`;
}

// ============================================================
// CAR CARD RENDERER
// ============================================================
function renderCarCard(car, linkPrefix = '') {
  const wishlisted = Storage.isWishlisted(car.id);
  const specItems  = [
    `<span class="spec-item"><i class="fas fa-users"></i> ${car.seats} Seats</span>`,
    `<span class="spec-item"><i class="fas fa-cog"></i> ${car.transmission}</span>`,
    `<span class="spec-item"><i class="fas fa-gas-pump"></i> ${car.fuel}</span>`,
    `<span class="spec-item"><i class="fas fa-tachometer-alt"></i> ${car.mileage}</span>`
  ];
  return `
    <div class="col" data-car-id="${car.id}">
      <div class="car-card">
        <div class="car-img-wrapper">
          <img src="${car.image}" alt="${car.brand} ${car.model}" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=600&q=80'">
          <button class="wishlist-btn ${wishlisted ? 'active' : ''}" onclick="handleWishlist('${car.id}', this)" title="Add to Wishlist">
            <i class="${wishlisted ? 'fas' : 'far'} fa-heart"></i>
          </button>
          <span class="availability-badge">
            <span class="badge-${car.available ? 'available' : 'unavailable'}">${car.available ? 'Available' : 'Unavailable'}</span>
          </span>
        </div>
        <div class="car-body">
          <p class="car-category"><i class="fas fa-tag me-1"></i>${car.category} · ${car.year}</p>
          <h6 class="car-title">${car.brand} ${car.model}</h6>
          <div class="d-flex align-items-center gap-1 mb-2">
            ${renderStars(car.rating)}
            <span class="rating-text">(${car.reviews} reviews)</span>
          </div>
          <div class="car-specs">${specItems.join('')}</div>
          <div class="d-flex align-items-center justify-content-between mt-2">
            <div>
              <div class="car-price">₹${car.price.toLocaleString()} <span>/ day</span></div>
            </div>
          </div>
          <div class="d-flex gap-2 mt-3">
            <a href="${linkPrefix}car-details.html?id=${car.id}" class="btn btn-outline-brand btn-sm-brand flex-fill">View Details</a>
            <a href="${linkPrefix}booking.html?id=${car.id}" class="btn btn-primary-brand btn-sm-brand flex-fill ${car.available ? '' : 'disabled'}" ${car.available ? '' : 'aria-disabled="true"'}>Book Now</a>
          </div>
        </div>
      </div>
    </div>`;
}

// ============================================================
// WISHLIST HANDLER (global)
// ============================================================
function handleWishlist(carId, btn) {
  const added = Storage.toggleWishlist(carId);
  const $btn  = $(btn);
  $btn.toggleClass('active', added);
  $btn.find('i').attr('class', added ? 'fas fa-heart' : 'far fa-heart');
  if (added) {
    Toast.success('Added to Wishlist', 'Car saved to your wishlist');
    $btn.addClass('pulse-once');
    setTimeout(() => $btn.removeClass('pulse-once'), 600);
  } else {
    Toast.info('Removed from Wishlist', 'Car removed from your wishlist');
  }
}

// ============================================================
// NAVBAR SCROLL HANDLER
// ============================================================
function initNavbarScroll() {
  const navbar = document.querySelector('.main-navbar');
  if (!navbar) return;
  const onScroll = () => navbar.classList.toggle('scrolled', window.scrollY > 40);
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
}

// ============================================================
// AOS-LIKE SCROLL ANIMATIONS
// ============================================================
function initScrollAnimations() {
  const targets = document.querySelectorAll('[data-aos]');
  if (!targets.length) return;
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) { e.target.classList.add('aos-animate'); observer.unobserve(e.target); }
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
  targets.forEach(t => observer.observe(t));
}

// ============================================================
// COUNTER ANIMATION
// ============================================================
function animateCounter(el, target, suffix = '') {
  const duration = 1800;
  const start    = performance.now();
  const step     = (now) => {
    const progress = Math.min((now - start) / duration, 1);
    const ease     = 1 - Math.pow(1 - progress, 3);
    el.textContent = Math.floor(ease * target).toLocaleString() + suffix;
    if (progress < 1) requestAnimationFrame(step);
  };
  requestAnimationFrame(step);
}

function initCounters() {
  const counters = document.querySelectorAll('[data-counter]');
  if (!counters.length) return;
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        const el     = e.target;
        const target = parseInt(el.dataset.counter);
        const suffix = el.dataset.suffix || '';
        animateCounter(el, target, suffix);
        observer.unobserve(el);
      }
    });
  }, { threshold: 0.3 });
  counters.forEach(c => observer.observe(c));
}

// ============================================================
// DATE HELPERS
// ============================================================
const DateHelper = {
  format(dateStr, options = {}) {
    if (!dateStr) return '—';
    const d = new Date(dateStr);
    if (isNaN(d)) return dateStr;
    const def = { day: 'numeric', month: 'short', year: 'numeric' };
    return d.toLocaleDateString('en-IN', { ...def, ...options });
  },
  daysBetween(start, end) {
    if (!start || !end) return 0;
    const s = new Date(start), e = new Date(end);
    return Math.max(Math.ceil((e - s) / (1000 * 60 * 60 * 24)), 0);
  },
  todayStr() {
    return new Date().toISOString().split('T')[0];
  },
  addDays(dateStr, n) {
    const d = new Date(dateStr);
    d.setDate(d.getDate() + n);
    return d.toISOString().split('T')[0];
  },
  isPast(dateStr) {
    return new Date(dateStr) < new Date();
  }
};

// ============================================================
// PRICE HELPERS
// ============================================================
const PriceHelper = {
  format(n)  { return '₹' + Number(n).toLocaleString('en-IN'); },
  tax(n)     { return Math.round(n * 0.18); },
  calcTotal(pricePerDay, days, extrasTotal = 0, discount = 0) {
    const base = pricePerDay * days;
    const tax  = this.tax(base + extrasTotal);
    return { base, tax, extrasTotal, discount, total: base + tax + extrasTotal - discount };
  }
};

// ============================================================
// FORM VALIDATION HELPERS
// ============================================================
const Validate = {
  email(v)  { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); },
  phone(v)  { return /^[6-9]\d{9}$/.test(v.replace(/\D/g,'')); },
  required(v){ return v && v.toString().trim().length > 0; },
  minLen(v,n){ return v.toString().trim().length >= n; },

  field(input, rule, msg) {
    const $i  = $(input);
    const val = $i.val().trim();
    const ok  = rule(val);
    $i.toggleClass('is-invalid', !ok).toggleClass('is-valid', ok);
    let fb = $i.siblings('.invalid-feedback');
    if (!fb.length) { fb = $('<div class="invalid-feedback"></div>'); $i.after(fb); }
    fb.text(ok ? '' : msg);
    return ok;
  },

  form($form) {
    let valid = true;
    $form.find('[required]').each(function() {
      const v = $(this).val().trim();
      if (!v) {
        $(this).addClass('is-invalid');
        valid = false;
      } else {
        $(this).removeClass('is-invalid').addClass('is-valid');
      }
    });
    return valid;
  }
};

// ============================================================
// COPY TO CLIPBOARD
// ============================================================
function copyToClipboard(text, successMsg = 'Copied!') {
  if (navigator.clipboard) {
    navigator.clipboard.writeText(text).then(() => Toast.success(successMsg, `"${text}" copied to clipboard`));
  } else {
    const ta = document.createElement('textarea');
    ta.value = text;
    document.body.appendChild(ta);
    ta.select();
    document.execCommand('copy');
    document.body.removeChild(ta);
    Toast.success(successMsg, `"${text}" copied to clipboard`);
  }
}

// ============================================================
// MOBILE SIDEBAR TOGGLE (Customer Dashboard)
// ============================================================
function initDashboardSidebar() {
  const sidebar  = document.querySelector('.dashboard-sidebar');
  const toggle   = document.getElementById('sidebarToggle');
  if (!sidebar || !toggle) return;

  toggle.addEventListener('click', () => sidebar.classList.toggle('open'));

  // Click outside to close
  document.addEventListener('click', (e) => {
    if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
      sidebar.classList.remove('open');
    }
  });
}

// ============================================================
// TABLE SEARCH FILTER
// ============================================================
function initTableSearch(inputSel, tableSel) {
  $(inputSel).on('input', function() {
    const q = $(this).val().toLowerCase();
    $(`${tableSel} tbody tr`).each(function() {
      $(this).toggle($(this).text().toLowerCase().includes(q));
    });
  });
}

// ============================================================
// PAGINATION HELPER
// ============================================================
const Pagination = {
  init(items, perPage, renderFn, containerSel, paginationSel) {
    let page = 1;
    const totalPages = () => Math.ceil(items.length / perPage);

    const render = () => {
      const start  = (page - 1) * perPage;
      const slice  = items.slice(start, start + perPage);
      $(containerSel).html(slice.map(renderFn).join(''));
      this.renderPaginator(paginationSel, page, totalPages(), (p) => { page = p; render(); });
    };

    render();
    return { setItems: (newItems) => { items = newItems; page = 1; render(); } };
  },

  renderPaginator(sel, current, total, onChange) {
    if (total <= 1) { $(sel).html(''); return; }
    let html = '<nav><ul class="pagination pagination-brand mb-0">';
    html += `<li class="page-item ${current===1?'disabled':''}"><a class="page-link" href="#" data-page="${current-1}"><i class="fas fa-chevron-left"></i></a></li>`;
    for (let i = 1; i <= total; i++) {
      html += `<li class="page-item ${i===current?'active':''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
    }
    html += `<li class="page-item ${current===total?'disabled':''}"><a class="page-link" href="#" data-page="${current+1}"><i class="fas fa-chevron-right"></i></a></li>`;
    html += '</ul></nav>';
    $(sel).html(html);
    $(sel).find('[data-page]').on('click', function(e) {
      e.preventDefault();
      const p = parseInt($(this).data('page'));
      if (p >= 1 && p <= total) onChange(p);
    });
  }
};

// ============================================================
// GLOBAL INIT
// ============================================================
$(document).ready(function() {
  Storage.seed();
  initNavbarScroll();
  initScrollAnimations();
  initCounters();
  initDashboardSidebar();

  // Update wishlist icon counts in nav
  const wlCount = Storage.getWishlist().length;
  $('.wishlist-count').text(wlCount || '');
});
