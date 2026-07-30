@extends('admin.layout.structure')

@section('content')
<div class="admin-page-content">
  <div class="admin-page-header">
    <div>
      <h4>Car Categories Management</h4>
      <div class="breadcrumb-brand"><span>Admin</span> <span class="sep">/</span> <span class="current">Categories</span></div>
    </div>
    <button class="btn btn-primary-brand btn-sm-brand" onclick="openCatModal()"><i class="fas fa-plus me-1"></i> Add Category</button>
  </div>

  <div class="row g-4" id="categoriesGrid">
    @forelse($categories as $c)
      <div class="col-12 col-sm-6 col-md-4 col-xl-3">
        <div class="bg-surface p-4 rounded-card border shadow-xs h-100 d-flex flex-column justify-content-between" style="min-width: 250px;">
          <div>
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="d-flex align-items-center gap-3">
                <div class="category-icon m-0" style="width:48px; height:48px; min-width:48px; font-size:1.25rem; background:var(--primary-lighter); color:var(--primary); border-radius:12px; display:flex; align-items:center; justify-content:center;"><i class="fas {{ $c->icon ?? 'fa-car' }}"></i></div>
                <div>
                  <h5 class="fw-700 font-heading mb-1 text-dark fs-5">{{ $c->name }}</h5>
                  <span class="badge bg-primary-lighter text-primary font-xs px-2 py-1">{{ $c->cars_count ?? 0 }} Vehicles</span>
                </div>
              </div>
            </div>
            <p class="font-sm text-muted mb-3">{{ $c->description ?? 'No description provided.' }}</p>
          </div>
          <div class="d-flex align-items-center gap-2 pt-3 border-top mt-auto">
            <button class="btn btn-sm btn-outline-primary flex-fill fw-600" onclick="editCat({{ $c->id }}, '{{ $c->name }}', '{{ $c->icon }}', '{{ addslashes($c->description) }}')"><i class="fas fa-edit me-1"></i> Edit</button>
            <a href="{{ url('/admin/del-category/' . $c->id) }}" onclick="return confirm('Are you sure you want to delete this category?')" class="btn btn-sm btn-outline-danger px-3"><i class="fas fa-trash-alt"></i></a>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="alert alert-info text-center py-4 mb-0">No categories found in database. Click "Add Category" above to create one.</div>
      </div>
    @endforelse
  </div>
</div>

<!-- ADD / EDIT CATEGORY MODAL -->
<div class="modal fade" id="catModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-700" id="catModalTitle">Add Vehicle Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="catForm" action="{{ url('/admin/save-category') }}" method="POST">
          @csrf
          <input type="hidden" name="cat_id" id="catId">
          <div class="mb-3">
            <label class="form-label font-sm">Category Name *</label>
            <input type="text" name="name" class="form-control" id="catName" required placeholder="e.g. Luxury, SUV, Sedan">
          </div>
          <div class="mb-3">
            <label class="form-label font-sm">Font Awesome Icon Class</label>
            <input type="text" name="icon" class="form-control" id="catIcon" value="fa-car-side" placeholder="e.g. fa-car-side, fa-gem, fa-truck-monster">
          </div>
          <div class="mb-3">
            <label class="form-label font-sm">Description</label>
            <textarea name="description" class="form-control" id="catDesc" rows="3" placeholder="Brief category description..."></textarea>
          </div>
          <button type="submit" class="btn btn-primary-brand w-100">Save Category</button>
        </form>
      </div>
    </div>
  </div>
</div>
@include('sweetalert::alert')
@endsection

@section('scripts')
<script>
  function openCatModal() {
    $('#catId').val('');
    $('#catName').val('');
    $('#catDesc').val('');
    $('#catIcon').val('fa-car-side');
    $('#catModalTitle').text('Add Vehicle Category');
    const modal = new bootstrap.Modal(document.getElementById('catModal'));
    modal.show();
  }

  function editCat(id, name, icon, desc) {
    $('#catId').val(id);
    $('#catName').val(name);
    $('#catIcon').val(icon || 'fa-car-side');
    $('#catDesc').val(desc || '');
    $('#catModalTitle').text(`Edit Category — ${name}`);
    const modal = new bootstrap.Modal(document.getElementById('catModal'));
    modal.show();
  }
</script>
@endsection
