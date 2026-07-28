@extends('admin.layout.structure')

@section('content')
<div class="admin-page-content">
  <div class="admin-page-header">
    <div>
      <h4>Promo Coupons & Deals Management</h4>
      <div class="breadcrumb-brand"><span>Admin</span> <span class="sep">/</span> <span class="current">Offers</span></div>
    </div>
    <button class="btn btn-primary-brand btn-sm-brand" onclick="openCouponModal()"><i class="fas fa-plus me-1"></i> Create New Coupon</button>
  </div>

  <div class="admin-table-card">
    <div class="admin-table-wrapper">
      <table class="table-brand">
        <thead>
          <tr><th>Code</th><th>Discount</th><th>Min Amount</th><th>Usage</th><th>Expiry</th><th>Status / Active Toggle</th><th>Actions</th></tr>
        </thead>
        <tbody id="adminCouponsTable">
          <!-- Rendered by JS -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ADD / EDIT COUPON MODAL -->
<div class="modal fade" id="couponModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-700" id="couponModalTitle">Create Promo Code</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="couponForm">
          <input type="hidden" id="cId">
          <div class="mb-3">
            <label class="form-label font-sm">Coupon Code *</label>
            <input type="text" class="form-control text-uppercase" id="cCode" required placeholder="SUMMER30">
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label font-sm">Discount Type</label>
              <select id="cType" class="form-select"><option value="percentage">Percentage (%)</option><option value="fixed">Fixed Amount (₹)</option></select>
            </div>
            <div class="col-6">
              <label class="form-label font-sm">Discount Value *</label>
              <input type="number" class="form-control" id="cValue" required placeholder="20">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label font-sm">Min Booking Amount (₹)</label>
            <input type="number" class="form-control" id="cMin" value="2000">
          </div>
          <div class="mb-3">
            <label class="form-label font-sm">Expiry Date</label>
            <input type="date" class="form-control" id="cExpiry" required value="2025-12-31">
          </div>
          <button type="submit" class="btn btn-primary-brand w-100">Save Coupon</button>
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
    renderCouponsAdmin();

    $('#couponForm').on('submit', function(e) {
      e.preventDefault();
      const editingId = $('#cId').val();
      const coupons = Storage.getCoupons();
      
      const c = {
        id: editingId || ('CP' + (coupons.length + 1)),
        code: $('#cCode').val().toUpperCase(),
        type: $('#cType').val(),
        value: parseInt($('#cValue').val()),
        minAmount: parseInt($('#cMin').val()),
        startDate: DateHelper.todayStr(),
        expiryDate: $('#cExpiry').val(),
        usageLimit: 500,
        used: editingId ? (coupons.find(x=>x.id===editingId)?.used || 0) : 0,
        status: 'Active',
        description: 'Promotional Offer',
        bgColor: '#2563eb'
      };

      Storage.saveCoupon(c);
      Toast.success('Saved', `Coupon ${c.code} saved successfully.`);
      const modal = bootstrap.Modal.getInstance(document.getElementById('couponModal'));
      modal.hide();
      renderCouponsAdmin();
    });
  });

  function renderCouponsAdmin() {
    const coupons = Storage.getCoupons();
    const html = coupons.map(c => `
      <tr>
        <td class="fw-700 font-mono text-primary">${c.code}</td>
        <td class="fw-700">${c.type === 'percentage' ? c.value + '%' : '₹' + c.value} OFF</td>
        <td>₹${c.minAmount.toLocaleString()}</td>
        <td class="font-sm">${c.used} / ${c.usageLimit}</td>
        <td class="font-sm text-muted">${c.expiryDate}</td>
        <td>
          <div class="form-check form-switch me-0">
            <input class="form-check-input" type="checkbox" ${c.status === 'Active' ? 'checked' : ''} onchange="toggleCouponStatus('${c.id}')">
            <label class="form-check-label font-xs fw-700 ${c.status === 'Active' ? 'text-success' : 'text-muted'}">${c.status}</label>
          </div>
        </td>
        <td>
          <div class="d-flex gap-1">
            <button class="btn btn-sm btn-outline-primary" onclick="editCoupon('${c.id}')"><i class="fas fa-edit"></i> Edit</button>
            <button class="btn btn-sm btn-outline-danger" onclick="delCoupon('${c.id}')"><i class="fas fa-trash-alt"></i></button>
          </div>
        </td>
      </tr>
    `).join('');
    $('#adminCouponsTable').html(html);
  }

  function openCouponModal() {
    $('#cId').val('');
    $('#cCode').val('');
    $('#cValue').val('');
    $('#couponModalTitle').text('Create New Promo Code');
    const modal = new bootstrap.Modal(document.getElementById('couponModal'));
    modal.show();
  }

  function editCoupon(id) {
    const c = Storage.getCoupons().find(x => x.id === id);
    if (!c) return;
    $('#cId').val(c.id);
    $('#cCode').val(c.code);
    $('#cType').val(c.type);
    $('#cValue').val(c.value);
    $('#cMin').val(c.minAmount);
    $('#cExpiry').val(c.expiryDate);
    $('#couponModalTitle').text(`Edit Coupon — ${c.code}`);
    const modal = new bootstrap.Modal(document.getElementById('couponModal'));
    modal.show();
  }

  function toggleCouponStatus(id) {
    const coupons = Storage.getCoupons();
    const c = coupons.find(x => x.id === id);
    if (c) {
      c.status = c.status === 'Active' ? 'Inactive' : 'Active';
      Storage.saveCoupon(c);
      Toast.info('Status Changed', `Coupon ${c.code} is now ${c.status}.`);
      renderCouponsAdmin();
    }
  }

  function delCoupon(id) {
    Storage.deleteCoupon(id);
    Toast.info('Coupon Deleted', 'Promo coupon removed.');
    renderCouponsAdmin();
  }
</script>
@endsection
