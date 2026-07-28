@extends('admin.layout.structure')

@section('content')
<div class="admin-page-content">
  <div class="admin-page-header">
    <div>
      <h4>Rental Branches & Locations</h4>
      <div class="breadcrumb-brand"><span>Admin</span> <span class="sep">/</span> <span class="current">Locations</span></div>
    </div>
    <button class="btn btn-primary-brand btn-sm-brand" onclick="openLocModal()"><i class="fas fa-plus me-1"></i> Add Branch Location</button>
  </div>

  <div class="admin-table-card">
    <div class="admin-table-wrapper">
      <table class="table-brand">
        <thead>
          <tr><th>ID</th><th>Branch Name</th><th>City & State</th><th>Address</th><th>Phone</th><th>Hours</th><th>Type</th><th>Actions</th></tr>
        </thead>
        <tbody id="adminLocTable">
          <!-- Rendered by JS -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ADD / EDIT LOCATION MODAL -->
<div class="modal fade" id="locModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-700" id="locModalTitle">Add Branch Location</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="locForm">
          <input type="hidden" id="lId">
          <div class="mb-3">
            <label class="form-label font-sm">Branch Name *</label>
            <input type="text" class="form-control" id="lName" required placeholder="e.g. Mumbai T2 Airport Hub">
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label font-sm">City *</label>
              <input type="text" class="form-control" id="lCity" required placeholder="Mumbai">
            </div>
            <div class="col-6">
              <label class="form-label font-sm">State *</label>
              <input type="text" class="form-control" id="lState" required placeholder="Maharashtra">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label font-sm">Full Street Address</label>
            <input type="text" class="form-control" id="lAddr" required placeholder="Terminal 2, CSIA Airport">
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label font-sm">Contact Phone</label>
              <input type="text" class="form-control" id="lPhone" required value="+91 22 6688 0000">
            </div>
            <div class="col-6">
              <label class="form-label font-sm">Location Type</label>
              <select id="lType" class="form-select"><option value="airport">Airport Hub</option><option value="city">City Center</option></select>
            </div>
          </div>
          <button type="submit" class="btn btn-primary-brand w-100">Save Location</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    Storage.seed();
    renderLocs();

    $('#locForm').on('submit', function(e) {
      e.preventDefault();
      const id = $('#lId').val();
      const locs = Storage.getLocations();

      const l = {
        id: id || ('LOC' + (locs.length + 1)),
        name: $('#lName').val(),
        city: $('#lCity').val(),
        state: $('#lState').val(),
        address: $('#lAddr').val(),
        phone: $('#lPhone').val(),
        hours: '24/7 Open',
        type: $('#lType').val()
      };

      Storage.saveLocation(l);
      Toast.success('Saved', `Location ${l.name} saved!`);
      const modal = bootstrap.Modal.getInstance(document.getElementById('locModal'));
      modal.hide();
      renderLocs();
    });
  });

  function renderLocs() {
    const locs = Storage.getLocations();
    const html = locs.map(l => `
      <tr>
        <td class="fw-700 font-mono">${l.id}</td>
        <td class="fw-700">${l.name}</td>
        <td>${l.city}, ${l.state}</td>
        <td class="font-sm text-secondary">${l.address}</td>
        <td class="font-sm">${l.phone}</td>
        <td><span class="badge bg-light text-dark font-xs">${l.hours}</span></td>
        <td><span class="badge bg-primary-lighter text-primary font-xs uppercase">${l.type}</span></td>
        <td>
          <div class="d-flex gap-1">
            <button class="btn btn-sm btn-outline-primary" onclick="editLoc('${l.id}')"><i class="fas fa-edit"></i> Edit</button>
            <button class="btn btn-sm btn-outline-danger" onclick="delLoc('${l.id}')"><i class="fas fa-trash-alt"></i></button>
          </div>
        </td>
      </tr>
    `).join('');
    $('#adminLocTable').html(html);
  }

  function openLocModal() {
    $('#lId').val('');
    $('#lName').val('');
    $('#lCity').val('');
    $('#lState').val('');
    $('#lAddr').val('');
    $('#locModalTitle').text('Add Branch Location');
    const modal = new bootstrap.Modal(document.getElementById('locModal'));
    modal.show();
  }

  function editLoc(id) {
    const l = Storage.getLocationById(id);
    if (!l) return;
    $('#lId').val(l.id);
    $('#lName').val(l.name);
    $('#lCity').val(l.city);
    $('#lState').val(l.state);
    $('#lAddr').val(l.address);
    $('#lPhone').val(l.phone);
    $('#lType').val(l.type);
    $('#locModalTitle').text(`Edit Location — ${l.name}`);
    const modal = new bootstrap.Modal(document.getElementById('locModal'));
    modal.show();
  }

  function delLoc(id) {
    Storage.deleteLocation(id);
    Toast.info('Location Deleted', 'Rental branch removed.');
    renderLocs();
  }
</script>
@endsection
