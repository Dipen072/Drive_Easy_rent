@extends('admin.layout.structure')

@section('content')
<div class="admin-page-content">
  
  <div class="admin-page-header">
    <div>
      <h4>Contact Messages & Inbox</h4>
      <div class="breadcrumb-brand"><span>Admin</span> <span class="sep">/</span> <span class="current">Contact Messages</span></div>
    </div>
  </div>

  <div class="admin-table-card">
    <div class="admin-table-header">
      <div class="admin-search-bar">
        <div class="admin-search-input">
          <i class="fas fa-search"></i>
          <input type="text" class="form-control" id="msgSearchInput" placeholder="Search sender, email, subject...">
        </div>
      </div>
    </div>
    <div class="admin-table-wrapper">
      <table class="table-brand">
        <thead>
          <tr>
            <th>#ID</th>
            <th>Sender</th>
            <th>Subject</th>
            <th>Phone</th>
            <th>Message Preview</th>
            <th>Date</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="messagesTableBody">
          @forelse($messages as $m)
          <tr>
            <td>#{{$m->id}}</td>
            <td class="fw-700">{{$m->name}}<div class="font-xs text-muted">{{$m->email}}</div></td>
            <td class="fw-600 text-primary">{{$m->subject}}</td>
            <td class="font-sm text-secondary">{{$m->phone ?? 'N/A'}}</td>
            <td class="font-sm text-secondary" style="max-width:260px;">"{{ Str::limit($m->message, 75) }}"</td>
            <td class="font-sm text-muted">{{ $m->created_at ? $m->created_at->format('Y-m-d H:i') : '' }}</td>
            <td><span class="badge-{{ strtolower($m->status) == 'unread' ? 'pending' : (strtolower($m->status) == 'replied' ? 'active' : 'completed') }}">{{ ucfirst($m->status ?? 'Unread') }}</span></td>
            <td>
              <div class="d-flex gap-1">
                <button class="btn btn-sm btn-outline-secondary" onclick='viewMessage(@json($m))' title="View Full Message"><i class="fas fa-eye"></i></button>
                <a href="{{ url('/admin/del_contact/'.$m->id) }}" onclick="return confirm('Are you sure you want to delete this message?')" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash-alt"></i></a>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center p-4 text-muted">No messages found in database.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>

<!-- VIEW MESSAGE MODAL -->
<div class="modal fade" id="viewMessageModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-700"><i class="fas fa-envelope-open me-2 text-primary"></i>Customer Inquiry</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="viewMsgBody">
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
  function viewMessage(m) {
    let createdAt = m.created_at ? m.created_at : '';
    $('#viewMsgBody').html(`
      <div class="p-2">
        <div class="fw-800 font-heading fs-5 text-primary mb-2">${m.subject}</div>
        <div class="font-sm text-muted mb-3 border-bottom pb-2">From: <strong>${m.name}</strong> (${m.email}) · Phone: ${m.phone || 'N/A'}</div>
        <div class="p-3 bg-surface-2 rounded font-sm text-dark lead" style="font-size:0.95rem;line-height:1.6;">
          "${m.message}"
        </div>
      </div>
    `);

    const modal = new bootstrap.Modal(document.getElementById('viewMessageModal'));
    modal.show();
  }

  $(document).ready(function() {
    $('#msgSearchInput').on('keyup', function() {
      var value = $(this).val().toLowerCase();
      $('#messagesTableBody tr').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
  });
</script>
@endsection
