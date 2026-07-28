/**
 * DriveEase — Admin Cars CRUD Logic
 * admin-cars.js
 */

$(document).ready(function() {
  Storage.seed();
  populateCategoryDropdown();
  renderCarsTable();

  $('#carTableSearch, #catFilter').on('input change', renderCarsTable);

  $('#toggleSidebarBtn').on('click', function() {
    $('#adminSidebar').toggleClass('collapsed');
    $('.admin-main, .admin-topbar').toggleClass('sidebar-collapsed');
  });
  $('#themeToggleBtn').on('click', function() { Storage.toggleTheme(); });
});

function populateCategoryDropdown() {
  const cats = Storage.getCategories();
  const $select = $('#catFilter');
  cats.forEach(c => $select.append(`<option value="${c.name}">${c.name}</option>`));
}

function renderCarsTable() {
  const q = $('#carTableSearch').val().toLowerCase().trim();
  const cat = $('#catFilter').val();

  let cars = Storage.getCars();

  if (q) {
    cars = cars.filter(c => c.brand.toLowerCase().includes(q) || c.model.toLowerCase().includes(q) || c.id.toLowerCase().includes(q));
  }
  if (cat !== 'all') {
    cars = cars.filter(c => c.category === cat);
  }

  $('#carCountBadge').text(cars.length);

  if (!cars.length) {
    $('#adminCarsTableBody').html('<tr><td colspan="8" class="text-center p-4 text-muted">No vehicles found.</td></tr>');
    return;
  }

  const html = cars.map(c => `
    <tr>
      <td><img src="${c.image}" class="rounded" style="width:50px;height:36px;object-fit:cover;"></td>
      <td class="fw-700 font-mono">${c.id}</td>
      <td class="fw-700">${c.brand} ${c.model} <small class="text-muted">(${c.year})</small></td>
      <td><span class="badge bg-primary-lighter text-primary font-xs">${c.category}</span></td>
      <td class="fw-700 text-primary">₹${c.price.toLocaleString()}/d</td>
      <td class="font-sm text-secondary">${c.location}</td>
      <td>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" ${c.available ? 'checked' : ''} onclick="toggleAvailability('${c.id}')">
        </div>
      </td>
      <td>
        <div class="d-flex gap-1">
          <a href="add-car.html?edit=${c.id}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
          <button class="btn btn-sm btn-outline-danger" onclick="deleteCarConfirm('${c.id}')" title="Delete"><i class="fas fa-trash-alt"></i></button>
        </div>
      </td>
    </tr>
  `).join('');

  $('#adminCarsTableBody').html(html);
}

function toggleAvailability(id) {
  const car = Storage.toggleCarAvailability(id);
  Toast.info('Availability Updated', `${car.brand} ${car.model} is now ${car.available ? 'Available' : 'Unavailable'}.`);
  renderCarsTable();
}

function deleteCarConfirm(id) {
  Confirm.show({
    title: 'Delete Vehicle?',
    message: `Are you sure you want to delete car ${id} from your fleet inventory?`,
    okText: 'Delete',
    type: 'danger',
    onConfirm: () => {
      Storage.deleteCar(id);
      Toast.success('Vehicle Deleted', `Car ${id} removed.`);
      renderCarsTable();
    }
  });
}
