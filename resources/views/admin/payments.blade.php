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
          <tr>
            <th>Txn ID</th>
            <th>Booking ID</th>
            <th>Customer</th>
            <th>Amount</th>
            <th>Method</th>
            <th>Date</th>
            <th>Payment Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="adminPayTable">
          <!-- Rendered dynamically by JS using DB records -->
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

  // Dynamic payments dataset from Database
  const dbPayments = @json($payments);

  $(document).ready(function() {
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
    const q = $('#paySearchInput').val().toLowerCase().trim();

    let list = dbPayments;

    if (currentPayFilter !== 'all') {
      list = list.filter(p => {
        const st = (p.payment_status || '').toLowerCase();
        if (currentPayFilter === 'Paid') {
          return st === 'paid' || st === 'successful';
        }
        return st === currentPayFilter.toLowerCase();
      });
    }

    if (q) {
      list = list.filter(p => {
        const txnId = (p.transaction_id || ('TXN' + (10000 + p.id))).toLowerCase();
        const bookingNo = (p.booking ? p.booking.booking_number : ('BK' + p.booking_id)).toLowerCase();
        const custName = p.customer ? (p.customer.first_name + ' ' + p.customer.last_name).toLowerCase() : 'guest customer';
        const method = (p.payment_method || '').toLowerCase();
        return txnId.includes(q) || bookingNo.includes(q) || custName.includes(q) || method.includes(q);
      });
    }

    if (!list.length) {
      $('#adminPayTable').html('<tr><td colspan="8" class="text-center p-4 text-muted">No transactions found matching filter.</td></tr>');
      return;
    }

    const html = list.map((p) => {
      const txnId = p.transaction_id || `TXN${10000 + p.id}`;
      const bookingNo = p.booking ? p.booking.booking_number : `BK${p.booking_id}`;
      const custName = p.customer ? `${p.customer.first_name} ${p.customer.last_name}` : (p.booking && p.booking.customer ? `${p.booking.customer.first_name} ${p.booking.customer.last_name}` : 'Guest Customer');
      const amount = parseFloat(p.amount || (p.booking ? p.booking.total_amount : 0)).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
      const method = p.payment_method || 'Online';
      
      let dateStr = 'N/A';
      if (p.created_at) {
        dateStr = new Date(p.created_at).toISOString().split('T')[0];
      }

      const st = p.payment_status || 'Pending';
      let badgeClass = 'badge-pending';
      let statusLabel = st;

      if (st === 'Paid' || st === 'Successful') {
        badgeClass = 'badge-completed';
        statusLabel = 'Successful';
      } else if (st === 'Refunded' || st === 'Failed') {
        badgeClass = 'badge-cancelled';
      }

      return `
        <tr>
          <td class="fw-700 font-mono">${txnId}</td>
          <td class="fw-700 font-mono text-primary">${bookingNo}</td>
          <td class="fw-600">${custName}</td>
          <td class="fw-800 text-dark">₹${amount}</td>
          <td><span class="badge bg-light text-dark font-xs">${method}</span></td>
          <td class="font-sm text-muted">${dateStr}</td>
          <td><span class="${badgeClass}">${statusLabel}</span></td>
          <td>
            <button class="btn btn-sm btn-outline-primary" onclick="viewPaymentDetail(${p.id})"><i class="fas fa-eye"></i> Details</button>
          </td>
        </tr>`;
    }).join('');

    $('#adminPayTable').html(html);
  }

  function viewPaymentDetail(paymentId) {
    const p = dbPayments.find(item => item.id === paymentId);
    if (!p) return;

    const txnId = p.transaction_id || `TXN${10000 + p.id}`;
    const bookingNo = p.booking ? p.booking.booking_number : `BK${p.booking_id}`;
    const custName = p.customer ? `${p.customer.first_name} ${p.customer.last_name}` : (p.booking && p.booking.customer ? `${p.booking.customer.first_name} ${p.booking.customer.last_name}` : 'Guest Customer');
    
    const b = p.booking || {};
    const basePrice = parseFloat(b.base_price || 0).toLocaleString('en-IN', { minimumFractionDigits: 2 });
    const extrasAmount = parseFloat(b.extras_amount || 0).toLocaleString('en-IN', { minimumFractionDigits: 2 });
    const taxAmount = parseFloat(b.tax_amount || 0).toLocaleString('en-IN', { minimumFractionDigits: 2 });
    const discountAmount = parseFloat(b.discount_amount || 0).toLocaleString('en-IN', { minimumFractionDigits: 2 });
    const totalAmount = parseFloat(p.amount || b.total_amount || 0).toLocaleString('en-IN', { minimumFractionDigits: 2 });
    
    let dateStr = 'N/A';
    if (p.created_at) {
      dateStr = new Date(p.created_at).toLocaleString();
    }

    const st = p.payment_status || 'Pending';
    let badgeClass = 'badge-pending';
    let statusLabel = st;

    if (st === 'Paid' || st === 'Successful') {
      badgeClass = 'badge-completed';
      statusLabel = 'Successful';
    } else if (st === 'Refunded' || st === 'Failed') {
      badgeClass = 'badge-cancelled';
    }

    $('#paymentModalBody').html(`
      <div class="p-2">
        <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
          <strong class="font-mono text-primary fs-5">${txnId}</strong>
          <span class="${badgeClass}">${statusLabel}</span>
        </div>
        <div class="row g-2 font-sm mb-3">
          <div class="col-6"><span class="text-muted">Booking Reference:</span><br><strong class="font-mono text-primary">${bookingNo}</strong></div>
          <div class="col-6"><span class="text-muted">Customer:</span><br><strong>${custName}</strong></div>
          <div class="col-6 me-0"><span class="text-muted">Payment Gateway / Method:</span><br><strong>${p.payment_gateway || p.payment_method || 'Online'}</strong></div>
          <div class="col-6 me-0"><span class="text-muted">Transaction Date:</span><br><strong>${dateStr}</strong></div>
        </div>
        <div class="bg-surface-2 p-3 rounded border font-sm">
          <div class="d-flex justify-content-between py-1"><span>Base Tariff:</span><strong>₹${basePrice}</strong></div>
          <div class="d-flex justify-content-between py-1"><span>Add-ons:</span><strong>₹${extrasAmount}</strong></div>
          <div class="d-flex justify-content-between py-1"><span>GST (18%):</span><strong>₹${taxAmount}</strong></div>
          <div class="d-flex justify-content-between py-1 text-danger"><span>Discount:</span><strong>-₹${discountAmount}</strong></div>
          <div class="d-flex justify-content-between py-1 border-top fw-800 fs-5 text-primary"><span>Total Captured:</span><span>₹${totalAmount}</span></div>
        </div>
      </div>
    `);

    const modal = new bootstrap.Modal(document.getElementById('paymentDetailModal'));
    modal.show();
  }
</script>
@endsection
