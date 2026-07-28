@extends('admin.layout.structure')

@section('content')
<div class="admin-page-content">
  <div class="admin-page-header">
    <div>
      <h4>Payment Transactions Log</h4>
      <div class="breadcrumb-brand"><span>Admin</span> <span class="sep">/</span> <span class="current">Payments</span></div>
    </div>
  </div>

  <!-- STATUS FILTER TABS -->
  <ul class="nav nav-pills tabs-brand mb-4" id="paymentStatusTabs">
    <li class="nav-item"><button class="nav-link active" data-status="all">All Payments</button></li>
    <li class="nav-item"><button class="nav-link" data-status="Paid">Successful</button></li>
    <li class="nav-item"><button class="nav-link" data-status="Pending">Pending</button></li>
    <li class="nav-item"><button class="nav-link" data-status="Failed">Failed</button></li>
    <li class="nav-item"><button class="nav-link" data-status="Refunded">Refunded</button></li>
  </ul>

  <div class="admin-table-card">
    <div class="admin-table-header">
      <div class="admin-search-bar">
        <div class="admin-search-input">
          <i class="fas fa-search"></i>
          <input type="text" class="form-control" id="paySearchInput" placeholder="Search Txn ID, Booking ID, Customer...">
        </div>
      </div>
    </div>
    <div class="admin-table-wrapper">
      <table class="table-brand">
        <thead>
          <tr><th>Txn ID</th><th>Booking ID</th><th>Customer</th><th>Amount</th><th>Method</th><th>Date</th><th>Payment Status</th><th>Action</th></tr>
        </thead>
        <tbody id="adminPayTable">
          <!-- Rendered by JS -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- PAYMENT DETAILS MODAL -->
<div class="modal fade" id="paymentDetailModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-700"><i class="fas fa-receipt me-2 text-primary"></i>Transaction Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="paymentModalBody">
        <!-- Rendered by JS -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  let currentPayFilter = 'all';

  $(document).ready(function() {
    Storage.seed();
    renderPayments();

    $('#paymentStatusTabs button').on('click', function() {
      $('#paymentStatusTabs button').removeClass('active');
      $(this).addClass('active');
      currentPayFilter = $(this).data('status');
      renderPayments();
    });

    $('#paySearchInput').on('input', renderPayments);
  });

  function renderPayments() {
    const bookings = Storage.getBookings();
    const q = $('#paySearchInput').val().toLowerCase().trim();

    let list = bookings;

    if (currentPayFilter !== 'all') {
      list = list.filter(b => b.paymentStatus.toLowerCase() === currentPayFilter.toLowerCase());
    }

    if (q) {
      list = list.filter(b => b.id.toLowerCase().includes(q) || b.customerName.toLowerCase().includes(q) || b.payment.toLowerCase().includes(q));
    }

    if (!list.length) {
      $('#adminPayTable').html('<tr><td colspan="8" class="text-center p-4 text-muted">No transactions found matching filter.</td></tr>');
      return;
    }

    const html = list.map((b, idx) => {
      const txnId = `TXN${10000 + idx}`;
      return `
        <tr>
          <td class="fw-700 font-mono">${txnId}</td>
          <td class="fw-700 font-mono text-primary">${b.id}</td>
          <td class="fw-600">${b.customerName}</td>
          <td class="fw-800 text-dark">₹${b.total.toLocaleString()}</td>
          <td><span class="badge bg-light text-dark font-xs">${b.payment}</span></td>
          <td class="font-sm text-muted">${b.createdAt || '2025-07-20'}</td>
          <td><span class="badge-${b.paymentStatus === 'Paid' ? 'completed' : (b.paymentStatus === 'Refunded' ? 'cancelled' : 'pending')}">${b.paymentStatus === 'Paid' ? 'Successful' : b.paymentStatus}</span></td>
          <td>
            <button class="btn btn-sm btn-outline-primary" onclick="viewPaymentDetail('${txnId}', '${b.id}')"><i class="fas fa-eye"></i> Details</button>
          </td>
        </tr>`;
    }).join('');

    $('#adminPayTable').html(html);
  }

  function viewPaymentDetail(txnId, bookingId) {
    const b = Storage.getBookingById(bookingId);
    if (!b) return;

    $('#paymentModalBody').html(`
      <div class="p-2">
        <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
          <strong class="font-mono text-primary fs-5">${txnId}</strong>
          <span class="badge-${b.paymentStatus === 'Paid' ? 'completed' : 'pending'}">${b.paymentStatus === 'Paid' ? 'Successful' : b.paymentStatus}</span>
        </div>
        <div class="row g-2 font-sm mb-3">
          <div class="col-6"><span class="text-muted">Booking Reference:</span><br><strong class="font-mono">${b.id}</strong></div>
          <div class="col-6"><span class="text-muted">Customer:</span><br><strong>${b.customerName}</strong></div>
          <div class="col-6 me-0"><span class="text-muted">Payment Gateway:</span><br><strong>${b.payment}</strong></div>
          <div class="col-6 me-0"><span class="text-muted">Transaction Date:</span><br><strong>${b.createdAt || '2025-07-20'}</strong></div>
        </div>
        <div class="bg-surface-2 p-3 rounded border font-sm">
          <div class="d-flex justify-content-between py-1"><span>Base Tariff:</span><strong>₹${b.basePrice.toLocaleString()}</strong></div>
          <div class="d-flex justify-content-between py-1"><span>Add-ons:</span><strong>₹${b.extras.toLocaleString()}</strong></div>
          <div class="d-flex justify-content-between py-1"><span>GST (18%):</span><strong>₹${b.tax.toLocaleString()}</strong></div>
          <div class="d-flex justify-content-between py-1 text-danger"><span>Discount:</span><strong>-₹${b.discount.toLocaleString()}</strong></div>
          <div class="d-flex justify-content-between py-1 border-top fw-800 fs-5 text-primary"><span>Total Captured:</span><span>₹${b.total.toLocaleString()}</span></div>
        </div>
      </div>
    `);

    const modal = new bootstrap.Modal(document.getElementById('paymentDetailModal'));
    modal.show();
  }
</script>
@endsection
