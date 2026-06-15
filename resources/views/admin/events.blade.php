@extends('layouts.app')

@section('content')
<!-- SECTION C: EVENT MANAGEMENT DASHBOARD -->
<div id="panel-events" class="admin-panel bg-white p-4 rounded-4 border">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h2>Event Management</h2>
      <p class="text-secondary fs-09">Deploy new hospitality events and configure nomination targets.</p>
    </div>
    <a class="btn btn-primary btn-sm bg-primary" href="{{ route('admin-new-event-form') }}"><i class="bi bi-calendar-plus me-1"></i> New Event</a>
  </div>

  <!-- Event List Table -->
  <div class="table-responsive">
    <table class="table table-hover align-middle" id="events-table">
      <thead>
        <tr class="table-light">
          <th>Event Name</th>
          <th>Event Type</th>
          <th>Timeline</th>
          <th>Max Nominees</th>
          <th>Nomination State</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($events as $event)
        <tr style="{{ $event->trashed() ? 'opacity: 0.5;' : '' }}">
          <td class="fw-semibold">
            <span>{{ $event->name }}</span>
            <div class="text-secondary small mt-1" style="font-size: 0.8rem;">
                Code: <code class="text-dark">{{ $event->event_code }}</code> | Location: <span>{{ $event->location }}</span> | GDPR: <span class="badge bg-light text-dark border">{{ $event->gdpr_compliance }}</span>
            </div>
            @if($event->type === 'hospitality')
              <div class="mt-2 p-2 border rounded bg-white small" style="font-size: 0.8rem; border-left: 3px solid #ff7a00 !important;">
                  <strong>Declaration:</strong> {{ $event->declaration }}<br>
                  <strong>Invite For:</strong> {{ $event->invite_for }}<br>
                  <strong>Invite Spouser:</strong> {{ $event->invite_spouser }}<br>
                  <strong>Govt/State Owned:</strong> {{ $event->govt_company }}
              </div>
            @endif
          </td>
          <td>{{ $event->type === 'hospitality' ? 'Hospitality' : 'Non-Hospitality' }}</td>
          <td>
            <div class="small fw-semibold text-dark">{{ $event->start_date }} to {{ $event->end_date }}</div>
            <div class="text-danger small mt-1" style="font-size: 0.75rem;">Deadline: {{ \Carbon\Carbon::parse($event->nomination_deadline)->format('M d, Y h:i A') }}</div>
          </td>
          <td>{{ $event->max_nominees_per_form }}</td>
          <td>
            @if($event->trashed())
              <span class="badge bg-secondary">Archived (Soft Deleted)</span>
            @else
              <span class="badge bg-warning text-dark">Awaiting Entries</span>
            @endif
          </td>
          <td>
            <button class="btn btn-sm btn-outline-secondary py-0 px-2" {{ $event->trashed() ? 'disabled' : '' }} onclick="alert('Editing...')">Edit</button>
            <button class="btn btn-sm btn-outline-danger py-0 px-2" {{ $event->trashed() ? 'disabled' : '' }} onclick="softDeleteEvent(this, {{ $event->id }})">Delete</button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

@push('scripts')
<script>
function softDeleteEvent(btn, eventId) {
    if (confirm("Are you sure you want to archive/delete this event?")) {
        fetch(`/admin/events/${eventId}/delete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const row = btn.closest('tr');
                row.style.opacity = '0.5';
                btn.disabled = true;
                const editBtn = btn.previousElementSibling;
                if (editBtn) editBtn.disabled = true;
                
                const statusBadge = row.cells[4].querySelector('.badge');
                if (statusBadge) {
                    statusBadge.className = 'badge bg-secondary';
                    statusBadge.textContent = 'Archived (Soft Deleted)';
                }
                showToast("Record archived/soft-deleted successfully.", "bg-secondary");
            }
        });
    }
}
</script>
@endpush
