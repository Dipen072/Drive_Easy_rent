@extends('admin.layout.structure')

@section('content')
<div class="admin-page-content">
  <div class="admin-page-header">
    <div>
      <h4>Car Manufacturers & Brands</h4>
      <div class="breadcrumb-brand"><span>Admin</span> <span class="sep">/</span> <span class="current">Brands</span></div>
    </div>
    <button class="btn btn-primary-brand btn-sm-brand" onclick="openBrandModal()"><i class="fas fa-plus me-1"></i> Add Brand</button>
  </div>

  <div class="row g-3 row-cols-2 row-cols-md-3 row-cols-lg-5" id="brandsGrid">
    <!-- Rendered by JS -->
  </div>
</div>

<!-- ADD / EDIT BRAND MODAL -->
<div class="modal fade" id="brandModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-700" id="brandModalTitle">Add Car Brand</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="brandForm">
          <input type="hidden" id="brandId">
          <div class="mb-3">
            <label class="form-label font-sm">Brand Name *</label>
            <input type="text" class="form-control" id="brandName" required placeholder="e.g. Tesla">
          </div>
          <button type="submit" class="btn btn-primary-brand w-100">Save Brand</button>
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
    renderBrands();

    $('#brandForm').on('submit', function(e) {
      e.preventDefault();
      const id = $('#brandId').val();
      const brands = Storage.getBrands();
      const b = {
        id: id || ('BR' + (brands.length + 1)),
        name: $('#brandName').val(),
        cars: id ? (brands.find(x=>x.id===id)?.cars || 0) : 0
      };
      Storage.saveBrand(b);
      Toast.success('Saved', `Brand ${b.name} saved!`);
      const modal = bootstrap.Modal.getInstance(document.getElementById('brandModal'));
      modal.hide();
      renderBrands();
    });
  });

  function renderBrands() {
    const brands = Storage.getBrands();
    const html = brands.map(b => `
      <div class="col">
        <div class="bg-surface p-3 text-center rounded-card border shadow-xs">
          <div class="fs-1 mb-1">🚗</div>
          <h6 class="fw-700 font-heading mb-1">${b.name}</h6>
          <span class="badge bg-primary-lighter text-primary font-xs mb-2">${b.cars} Vehicles</span>
          <div class="d-flex justify-content-center gap-1 border-top pt-2 mt-1">
            <button class="btn btn-xs btn-outline-primary" onclick="editBrand('${b.id}')"><i class="fas fa-edit"></i> Edit</button>
            <button class="btn btn-xs btn-outline-danger" onclick="delBrand('${b.id}')"><i class="fas fa-trash-alt"></i> Delete</button>
          </div>
        </div>
      </div>
    `).join('');
    $('#brandsGrid').html(html);
  }

  function openBrandModal() {
    $('#brandId').val('');
    $('#brandName').val('');
    $('#brandModalTitle').text('Add Car Brand');
    const modal = new bootstrap.Modal(document.getElementById('brandModal'));
    modal.show();
  }

  function editBrand(id) {
    const b = Storage.getBrands().find(x => x.id === id);
    if (!b) return;
    $('#brandId').val(b.id);
    $('#brandName').val(b.name);
    $('#brandModalTitle').text(`Edit Brand — ${b.name}`);
    const modal = new bootstrap.Modal(document.getElementById('brandModal'));
    modal.show();
  }

  function delBrand(id) {
    Storage.deleteBrand(id);
    Toast.info('Brand Deleted', 'Manufacturer brand removed.');
    renderBrands();
  }
</script>
@endsection
