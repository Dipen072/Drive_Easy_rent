@extends('website.layout.structure')

@section('content')
<style>
  .dashboard-layout { display: flex; min-height: calc(100vh - 120px); }
  .dashboard-sidebar { width: 260px; background: var(--surface); border-right: 1px solid var(--border); padding: 1.5rem 1rem; flex-shrink: 0; }
  .dashboard-content { flex: 1; padding: 2rem; background: var(--surface-2); }
  .dash-nav-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; color: var(--text-secondary); border-radius: var(--radius-sm); font-weight: 600; text-decoration: none; margin-bottom: 0.25rem; transition: var(--transition); }
  .dash-nav-item:hover, .dash-nav-item.active { background: var(--primary-lighter); color: var(--primary); }
  @media (max-width: 991.98px) {
    .dashboard-layout { flex-direction: column; }
    .dashboard-sidebar { width: 100%; border-right: none; border-bottom: 1px solid var(--border); }
  }
</style>

<div class="dashboard-layout">
  <!-- SIDEBAR -->
  <aside class="dashboard-sidebar">
    <div class="text-center pb-3 mb-3 border-bottom">
      <img src="{{ $customer->profile_picture ? url($customer->profile_picture) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&q=80' }}" class="rounded-circle mb-2" style="width:70px;height:70px;object-fit:cover;">
      <h6 class="fw-800 font-heading mb-0">{{ $customer->first_name }} {{ $customer->last_name }}</h6>
      <span class="badge bg-success-light text-success font-xs fw-700">Account {{ $customer->status ?? 'Active' }}</span>
    </div>

    <a href="{{url('/index')}}" class="dash-nav-item"><i class="fas fa-th-large"></i> Home</a>
    <a href="{{url('/my-bookings')}}" class="dash-nav-item active"><i class="fas fa-calendar-check"></i> My Bookings</a>
    <a href="{{url('/user_profile')}}" class="dash-nav-item"><i class="fas fa-user-circle"></i> My Profile</a>
    <a href="{{url('/cars')}}" class="dash-nav-item"><i class="fas fa-car-side"></i> Browse Cars</a>
    <a href="{{url('/offers')}}" class="dash-nav-item"><i class="fas fa-tag"></i> Special Offers</a>
    <a href="{{url('/user_logout')}}" class="dash-nav-item text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="dashboard-content">
    <div class="max-w-1000 mx-auto">
      <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
          <h3 class="fw-800 font-heading mb-1">My Reservations</h3>
          <p class="text-secondary font-sm mb-0">View all your current, upcoming, completed, and cancelled car rentals.</p>
        </div>
        <a href="{{ url('/cars') }}" class="btn btn-primary-brand btn-sm"><i class="fas fa-plus me-1"></i> Book New Car</a>
      </div>

      <div class="bg-surface p-4 rounded-card border shadow-xs">
        @if($bookings->isEmpty())
          <div class="text-center py-5">
            <i class="fas fa-car-side text-muted fs-1 mb-3"></i>
            <h5 class="fw-700 text-secondary">No Bookings Found</h5>
            <p class="text-muted font-sm mb-4">You have not placed any car rental reservations yet.</p>
            <a href="{{ url('/cars') }}" class="btn btn-primary-brand">Browse Vehicles Now</a>
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Booking No</th>
                  <th>Vehicle</th>
                  <th>Pickup</th>
                  <th>Return</th>
                  <th>Total</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($bookings as $b)
                <tr>
                  <td class="fw-700 font-mono text-primary">{{ $b->booking_number }}</td>
                  <td>
                    <div class="d-flex align-items-center gap-2">
                      <img src="{{ asset($b->car->image) }}" class="rounded" style="width:50px;height:35px;object-fit:cover;">
                      <span class="fw-600 font-sm">{{ $b->car->brand_name }} {{ $b->car->model_name }}</span>
                    </div>
                  </td>
                  <td class="font-sm">{{ \Carbon\Carbon::parse($b->pickup_date)->format('d M Y') }}</td>
                  <td class="font-sm">{{ \Carbon\Carbon::parse($b->return_date)->format('d M Y') }}</td>
                  <td class="fw-700">₹{{ number_format($b->total_amount, 2) }}</td>
                  <td>
                    @if($b->booking_status === 'Confirmed')
                      <span class="badge bg-success-light text-success fw-700">Confirmed</span>
                    @elseif($b->booking_status === 'Active')
                      <span class="badge bg-primary-light text-primary fw-700">Active</span>
                    @elseif($b->booking_status === 'Completed')
                      <span class="badge bg-secondary-light text-secondary fw-700">Completed</span>
                    @elseif($b->booking_status === 'Cancelled')
                      <span class="badge bg-danger-light text-danger fw-700">Cancelled</span>
                    @else
                      <span class="badge bg-warning-light text-warning fw-700">Pending</span>
                    @endif
                  </td>
                  <td>
                    <div class="d-flex gap-1">
                      <a href="{{ url('/my-bookings/' . $b->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i> Details</a>
                      @if(!in_array($b->booking_status, ['Completed', 'Cancelled']))
                        <button class="btn btn-sm btn-outline-danger" onclick="cancelCustomerBooking({{ $b->id }})"><i class="fas fa-times-circle"></i> Cancel</button>
                      @endif
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
  </main>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
  function cancelCustomerBooking(bookingId) {
    const reason = prompt('Please specify cancellation reason:');
    if (reason === null) return;

    const csrfToken = '{{ csrf_token() }}';
    $.ajax({
      url: '/booking/' + bookingId + '/cancel',
      method: 'POST',
      data: {
        _token: csrfToken,
        reason: reason
      },
      success: function(res) {
        if (res.success) {
          alert(res.message);
          window.location.reload();
        }
      },
      error: function(xhr) {
        alert(xhr.responseJSON ? xhr.responseJSON.message : 'Error cancelling booking.');
      }
    }
  });
</script>
@endsection
