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
          <th>Event Code</th>
          <th>Event Name</th>
          <th>Event Type</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Location</th>
          <th>Account Domain Limitation</th>
          <th>Nomination Deadline Date</th>
          <th>Event Status</th>
          <th>Nomination Requirement</th>
          <th>Nominator Name</th>
          <th>Assigned Unit Ops</th>
          <th>Nominations</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($events as $event)
        <tr>
          <td><code class="text-dark">{{ $event->event_code }}</code></td>
          <td class="fw-semibold">
            <span>{{ $event->name }}</span>
            @if($event->type === 'hospitality')
              {{-- Hospitality extra meta details block --}}
            @endif
          </td>
          <td>{{ $event->type === 'hospitality' ? 'Hospitality' : 'Non-Hospitality' }}</td>
          <td>
            {{ $event->start_date  }}</div>
          </td>
          <td>
            {{ $event->end_date  }}</div>
          </td>
          <td>{{ $event->location }}</td>
          <td>
            <span class="text-secondary small">{{ $event->domain_limitations }}</span>
          </td>
          <td>
            {{ $event->nomination_deadline  }}</div>
          </td>
          <td>
            @if($event->status === 'ongoing')
              <span class="badge bg-success event-status-badge">Ongoing</span>
            @elseif($event->status === 'completed')
              <span class="badge bg-secondary event-status-badge">Completed</span>
            @else
              <span class="badge bg-danger event-status-badge">Cancelled</span>
            @endif
          </td>
          <td>{{ $event->max_nominees_per_form }} max</td>
          <td>
            <span class="text-secondary small">{{ $event->assigned_nominator_names }}</span>
          </td>
          <td>
            <span class="text-secondary small">{{ $event->assigned_unit_spoc_names }}</span>
          </td>
          <td>
            <div class="d-flex justify-content-center gap-3 align-items-center">
              <div class="d-flex align-items-center">
                <i class="bi bi-people text-primary me-2"></i><span>{{ $event->nominees_count }}</span>
              </div>
              <div class="vr text-light-grey"></div>
              <button
                class="btn btn-sm btn-outline-primary py-0 px-2"
                data-bs-toggle="modal"
                data-bs-target="#nominationListModal-{{ $event->id }}"
              >
                View List
              </button>
            </div>
          </td>
          <td>
            <button class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="openEditEventModal({
                id: {{ $event->id }},
                event_code: '{{ addslashes($event->event_code) }}',
                name: '{{ addslashes($event->name) }}',
                start_date: '{{ $event->raw_start_date }}',
                end_date: '{{ $event->raw_end_date }}',
                location: '{{ addslashes($event->location) }}',
                type: '{{ $event->type === 'hospitality' ? 'Hospitality' : 'Non-Hospitality' }}',
                scope: '{{ $event->scope }}',
                nomination_deadline: '{{ $event->raw_nomination_deadline }}',
                nomination_limit: {{ $event->max_nominees_per_form }},
                gdpr_compliance: '{{ addslashes($event->gdpr_compliance) }}',
                declaration: '{{ addslashes($event->declaration) }}',
                invite_for: '{{ addslashes($event->invite_for) }}',
                invite_spouser: '{{ addslashes($event->invite_spouser) }}',
                govt_company: '{{ addslashes($event->govt_company) }}',
                assigned_unit_spocs: {{ json_encode($event->assigned_unit_spoc_ids) }}
            })">Edit</button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<!-- Edit Event Modal -->
<div class="modal fade" id="editEventModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header border-0 bg-light py-3">
        <h5 class="modal-title fw-bold">Edit Event</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4">
        <form id="edit-event-form" method="post" action="">
          @csrf
          <div class="row g-3">
            <!-- Event Code -->
            <div class="col-md-6">
              <label class="form-label fw-bold small">Event Code</label>
              <input type="text" name="event_code" id="edit-form-event-code" class="form-control form-control-sm" required placeholder="e.g. EVT-2026-001" />
            </div>
            <!-- Event Name -->
            <div class="col-md-6">
              <label class="form-label fw-bold small">Event Name</label>
              <input type="text" name="name" id="edit-form-event-name" class="form-control form-control-sm" required placeholder="e.g. Annual Summit" />
            </div>
            <!-- Event Start Date -->
            <div class="col-md-6">
              <label class="form-label fw-bold small">Event Start Date</label>
              <input type="date" name="start_date" id="edit-form-event-start-date" class="form-control form-control-sm" required />
            </div>
            <!-- Event End Date -->
            <div class="col-md-6">
              <label class="form-label fw-bold small">Event End Date</label>
              <input type="date" name="end_date" id="edit-form-event-end-date" class="form-control form-control-sm" required />
            </div>
            <!-- Event Location -->
            <div class="col-md-6">
              <label class="form-label fw-bold small">Event Location</label>
              <input type="text" name="location" id="edit-form-event-location" class="form-control form-control-sm" required placeholder="e.g. San Francisco, CA" />
            </div>
             <!-- Event Type -->
            <div class="col-md-6">
              <label class="form-label fw-bold small">Event Type</label>
              <select name="type" id="edit-form-event-type" class="form-select form-select-sm" required onchange="toggleEditHospitalityFields()">
                <option value="Hospitality">Hospitality</option>
                <option value="Non-Hospitality">Non-Hospitality</option>
              </select>
            </div>
            <!-- Event Scope -->
            <div class="col-md-6">
              <label class="form-label fw-bold small">Event Scope</label>
              <select name="scope" id="edit-form-event-scope" class="form-select form-select-sm" required>
                <option value="internal">Internal</option>
                <option value="external">External</option>
              </select>
            </div>
            <!-- Event Nomination Deadline -->
            <div class="col-md-6">
              <label class="form-label fw-bold small">Event Nomination Deadline</label>
              <input type="datetime-local" name="nomination_deadline" id="edit-form-event-deadline" class="form-control form-control-sm" required />
            </div>
            <!-- Event Nomination Limit -->
            <div class="col-md-6">
              <label class="form-label fw-bold small">Event Nomination Limit</label>
              <input type="number" name="nomination_limit" id="edit-form-event-limit" class="form-control form-control-sm" required min="1" />
            </div>
            <!-- GDPR Compliance -->
            <div class="col-md-6">
              <label class="form-label fw-bold small">GDPR Compliance</label>
              <select name="gdpr_compliance" id="edit-form-event-gdpr" class="form-select form-select-sm" required>
                @foreach($gdprOptions as $option)
                  <option value="{{ $option->name }}">{{ $option->name }}</option>
                @endforeach
              </select>
            </div>

            <!-- Assigned Unit SPOCs/Ops -->
            <div class="col-md-12">
              <label class="form-label fw-bold small">Assign Unit Ops</label>
              <div class="border rounded p-3 bg-white scrollable-content-box" style="max-height: 150px; overflow-y: auto;">
                <div class="row g-2">
                  @forelse($unitSpocs as $spoc)
                    <div class="col-sm-6 col-md-4">
                      <div class="form-check">
                        <input class="form-check-input border-primary edit-form-spoc-checkbox" type="checkbox" name="unit_spocs[]" value="{{ $spoc->id }}" id="edit-spoc-{{ $spoc->id }}">
                        <label class="form-check-label small" for="edit-spoc-{{ $spoc->id }}">
                          {{ $spoc->name }} {{ $spoc->last_name }} ({{ $spoc->email }})
                        </label>
                      </div>
                    </div>
                  @empty
                    <div class="col-12 text-muted small">No active Unit Ops found.</div>
                  @endforelse
                </div>
              </div>
              <span class="text-secondary small d-block mt-1">Select the Unit SPOCs (Unit Ops) to assign to this event.</span>
            </div>

            <!-- Conditional Hospitality Fields Container -->
            <div id="edit-hospitality-fields-container" class="col-12 border rounded p-3 bg-white mt-3 shadow-sm">
              <p class="fw-bold text-orange mb-3"><i class="bi bi-gift-fill me-1"></i> Hospitality Details</p>
              <div class="row g-3">
                <!-- Declaration -->
                <div class="col-md-6">
                  <label class="form-label fw-bold small">Declaration</label>
                  <input type="text" name="declaration" id="edit-form-event-declaration" class="form-control form-control-sm" placeholder="Enter declaration text" />
                </div>
                <!-- Invite For -->
                <div class="col-md-6">
                  <label class="form-label fw-bold small">Invite For</label>
                  <input type="text" name="invite_for" id="edit-form-event-invite-for" class="form-control form-control-sm" placeholder="e.g. Both Men's Finals" />
                </div>
                <!-- Invite Spouser -->
                <div class="col-md-6">
                  <label class="form-label fw-bold small">Invite Spouser</label>
                  <input type="text" name="invite_spouser" id="edit-form-event-invite-spouser" class="form-control form-control-sm" placeholder="Spouse details" />
                </div>
                <!-- Govt/State Owned Company -->
                <div class="col-md-6">
                  <label class="form-label fw-bold small">Govt/State Owned Company</label>
                  <input type="text" name="govt_company" id="edit-form-event-govt-company" class="form-control form-control-sm" placeholder="Govt/State Owned Company details" />
                </div>
              </div>
            </div>

            <!-- Submit & Cancel Buttons -->
            <div class="col-12 text-end mt-4">
              <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 me-2" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-sm btn-primary bg-primary rounded-3">Save Changes</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@foreach($events as $event)
<!-- Nomination list modal for {{ $event->name }} -->
<div class="modal fade" id="nominationListModal-{{ $event->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header border-0 bg-light py-3 justify-content-between">
        <h5 class="modal-title fw-bold">Nominations List - {{ $event->name }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4">
        <div class="table-responsive border rounded-3 bg-white">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr class="table-light">
                <th class="ps-3">S.No.</th>
                <th>Nominator</th>
                <th>Nominee Name</th>
                <th>Email</th>
                <th>Invite Status</th>
                <th>Approval Status</th>
                <th>GDPR Option</th>
                <th>Unit / Sub-Unit</th>
                <th>Company</th>
                <th>Job Title / Level</th>
                <th>AM Details</th>
                <th>Business/IT</th>
                <th>Country</th>
              </tr>
            </thead>
            <tbody>
              @forelse($event->nominees as $nominee)
              <tr>
                <td class="ps-3">{{ $loop->iteration }}</td>
                <td>
                  <div class="fw-semibold">{{ $nominee->nominator_name }}</div>
                  <div class="text-secondary small">{{ $nominee->nominator_email }}</div>
                </td>
                <td class="fw-semibold">{{ $nominee->full_name }}</td>
                <td>{{ $nominee->email }}</td>
                <td>
                  @if($nominee->invite_status)
                    <span class="badge bg-secondary">{{ $nominee->invite_status }}</span>
                  @else
                    <span class="text-muted small">-</span>
                  @endif
                </td>
                <td>
                  @if($nominee->approval_status === 'approved')
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">Approved</span>
                  @elseif($nominee->approval_status === 'pending')
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1">Pending</span>
                  @else
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1">Rejected</span>
                  @endif
                </td>
                <td>
                  <span class="text-secondary small">{{ $nominee->gdpr_compliance }}</span>
                </td>
                <td>
                  <div>{{ $nominee->unit }}</div>
                  @if($nominee->sub_unit)
                    <div class="text-secondary small">{{ $nominee->sub_unit }}</div>
                  @endif
                </td>
                <td>{{ $nominee->company }}</td>
                <td>
                  <div>{{ $nominee->title }}</div>
                  <div class="text-secondary small">Level {{ $nominee->job_level }}</div>
                </td>
                <td>
                  <div class="small">AM: {{ $nominee->primary_account_manager_name }}</div>
                  <div class="text-secondary small">{{ $nominee->primary_account_manager_email }}</div>
                </td>
                <td>{{ $nominee->business_or_it }}</td>
                <td>{{ $nominee->country }}</td>
              </tr>
              @empty
              <tr>
                <td colspan="13" class="text-center text-secondary py-4">No nominations submitted yet for this event.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer border-0 bg-light py-2">
        <button type="button" class="btn btn-sm btn-secondary rounded-3" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endforeach
@endsection

@push('scripts')
<script>
let editEventModalInstance = null;

document.addEventListener('DOMContentLoaded', () => {
    editEventModalInstance = new bootstrap.Modal(document.getElementById('editEventModal'));
});

function openEditEventModal(eventData) {
    const form = document.getElementById('edit-event-form');
    form.action = `/admin/events/${eventData.id}/update`;
    
    document.getElementById('edit-form-event-code').value = eventData.event_code;
    document.getElementById('edit-form-event-name').value = eventData.name;
    document.getElementById('edit-form-event-start-date').value = eventData.start_date;
    document.getElementById('edit-form-event-end-date').value = eventData.end_date;
    document.getElementById('edit-form-event-location').value = eventData.location;
    document.getElementById('edit-form-event-type').value = eventData.type;
    document.getElementById('edit-form-event-scope').value = eventData.scope;
    document.getElementById('edit-form-event-deadline').value = eventData.nomination_deadline;
    document.getElementById('edit-form-event-limit').value = eventData.nomination_limit;
    document.getElementById('edit-form-event-gdpr').value = eventData.gdpr_compliance;
    
    document.getElementById('edit-form-event-declaration').value = eventData.declaration || '';
    document.getElementById('edit-form-event-invite-for').value = eventData.invite_for || '';
    document.getElementById('edit-form-event-invite-spouser').value = eventData.invite_spouser || '';
    document.getElementById('edit-form-event-govt-company').value = eventData.govt_company || '';
    
    // Reset unit spocs checkboxes
    document.querySelectorAll('.edit-form-spoc-checkbox').forEach(cb => {
        cb.checked = false;
    });
    // Check assigned unit spocs
    if (eventData.assigned_unit_spocs) {
        eventData.assigned_unit_spocs.forEach(id => {
            const cb = document.getElementById(`edit-spoc-${id}`);
            if (cb) cb.checked = true;
        });
    }
    
    toggleEditHospitalityFields();
    editEventModalInstance.show();
}

function toggleEditHospitalityFields() {
    const type = document.getElementById('edit-form-event-type').value;
    const container = document.getElementById('edit-hospitality-fields-container');
    const inputs = container.querySelectorAll('input');
    
    if (type === 'Hospitality') {
        container.classList.remove('d-none');
        inputs.forEach(input => {
            input.required = true;
        });
    } else {
        container.classList.add('d-none');
        inputs.forEach(input => {
            input.required = false;
        });
    }
}

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
                
                const statusBadge = row.querySelector('.event-status-badge');
                if (statusBadge) {
                    statusBadge.className = 'badge bg-secondary event-status-badge';
                    statusBadge.textContent = 'Archived (Soft Deleted)';
                }
                showToast("Record archived/soft-deleted successfully.", "bg-secondary");
            }
        });
    }
}
</script>
@endpush
