@extends('admin.layout.structure')

@section('content')
<div class="admin-page-content">
  <div class="admin-page-header">
    <div>
      <h4>Moderate Customer Reviews</h4>
      <div class="breadcrumb-brand"><span>Admin</span> <span class="sep">/</span> <span class="current">Reviews</span></div>
    </div>
  </div>

  <div class="admin-table-card">
    <div class="admin-table-wrapper">
      <table class="table-brand">
        <thead>
          <tr><th>Customer</th><th>Car Rented</th><th>Rating</th><th>Review Snippet</th><th>Date</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody id="adminReviewsTable">
          <!-- Rendered by JS -->
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    Storage.seed();
    renderReviewsAdmin();
  });

  function renderReviewsAdmin() {
    const reviews = Storage.getReviews();
    const html = reviews.map(r => `
      <tr>
        <td class="fw-700">${r.customerName}</td>
        <td>${r.carName}</td>
        <td><span class="star-rating font-sm">${renderStars(r.rating, false)}</span> <strong class="ms-1 font-xs">${r.rating}★</strong></td>
        <td class="font-sm text-secondary" style="max-width:250px;">"${r.review.substring(0, 70)}..."</td>
        <td class="font-sm text-muted">${r.date}</td>
        <td><span class="badge-${r.status === 'Approved' ? 'active' : 'pending'}">${r.status}</span></td>
        <td>
          <div class="d-flex gap-1">
            ${r.status !== 'Approved' ? `<button class="btn btn-sm btn-outline-success" onclick="updateReview('${r.id}', 'Approved')"><i class="fas fa-check"></i> Approve</button>` : ''}
            <button class="btn btn-sm btn-outline-danger" onclick="delReview('${r.id}')"><i class="fas fa-trash-alt"></i></button>
          </div>
        </td>
      </tr>
    `).join('');
    $('#adminReviewsTable').html(html);
  }

  function updateReview(id, status) {
    Storage.updateReviewStatus(id, status);
    Toast.success('Review Approved', `Review ${id} published.`);
    renderReviewsAdmin();
  }

  function delReview(id) {
    Storage.deleteReview(id);
    Toast.info('Review Deleted', `Review ${id} removed.`);
    renderReviewsAdmin();
  }
</script>
@endsection
