@extends('layouts.app')

@section('content')
<!-- SECTION: CUSTOM NOMINATOR LIMITS -->
<div id="panel-nominator-limits" class="admin-panel bg-white p-4 rounded-4 border">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h2 class="fw-bold text-dark">Custom Nominator Limits</h2>
      <p class="text-secondary fs-09">Set custom nominee thresholds for specific nominators per event.</p>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="border rounded-4 p-3 bg-light text-start shadow-sm border-light-grey">
        <span class="text-secondary uppercase fs-07 fw-bold d-block">Total Custom Limits</span>
        <h3 class="fw-bold mt-1 mb-0 text-primary" id="stat-total-limits">{{ $limits->count() }}</h3>
      </div>
    </div>
  </div>

  <!-- Action Bar -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div class="position-relative w-50 max-width-300">
      <input type="text" id="search-limits" class="form-control form-control-sm ps-4 rounded-3" placeholder="Search limits..." onkeyup="filterLimitsTable()">
      <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-2 text-secondary fs-09"></i>
    </div>
    <button class="btn btn-primary btn-sm rounded-3 px-3 bg-primary" onclick="openAddLimitModal()">
      <i class="bi bi-plus-lg me-1"></i>Add Custom Limit
    </button>
  </div>

  <!-- Table -->
  <div class="table-responsive border rounded-3 bg-white">
    <table class="table table-hover align-middle mb-0" id="limits-table">
      <thead>
        <tr class="table-light">
          <th class="text-start ps-4">Event Name</th>
          <th class="text-start">Nominator Name</th>
          <th class="text-start">Email</th>
          <th>Max Nominees</th>
          <th>Created By</th>
          <th>Updated By</th>
          <th style="width: 150px">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($limits as $limit)
        <tr id="limit-row-{{ $limit->id }}">
          <td class="text-start ps-4 fw-semibold event-name">{{ $limit->event ? $limit->event->name : 'N/A' }}</td>
          <td class="text-start nominator-name">{{ $limit->nominator ? ($limit->nominator->name . ' ' . $limit->nominator->last_name) : 'N/A' }}</td>
          <td class="text-start nominator-email text-muted small">{{ $limit->nominator ? $limit->nominator->email : 'N/A' }}</td>
          <td class="fw-bold text-center">{{ $limit->max_nominees }}</td>
          <td class="text-secondary small">{{ $limit->creator ? $limit->creator->name : 'System' }}</td>
          <td class="text-secondary small">{{ $limit->updater ? $limit->updater->name : 'System' }}</td>
          <td>
            <button class="btn btn-sm btn-outline-primary me-1 py-0 px-2" onclick="openEditLimitModal({{ $limit->id }}, '{{ addslashes($limit->event ? $limit->event->name : 'N/A') }}', '{{ addslashes($limit->nominator ? ($limit->nominator->name . ' ' . $limit->nominator->last_name) : 'N/A') }}', {{ $limit->max_nominees }})">
              <i class="bi bi-pencil"></i>
            </button>
            <button class="btn btn-sm btn-outline-danger py-0 px-2" onclick="deleteLimit({{ $limit->id }})">
              <i class="bi bi-trash"></i>
            </button>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center py-4 text-secondary">No custom nominator limits defined yet.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Add Limit Modal -->
<div class="modal fade" id="addLimitModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header border-0 bg-light py-3">
        <h5 class="modal-title fw-bold">Add Custom Nominator Limit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4">
        <form id="add-limit-form">
          <div class="mb-3">
            <label class="form-label fw-bold">Active Event</label>
            <select name="event_id" class="form-select rounded-3" required>
              <option value="">Select Event...</option>
              @foreach($events as $event)
                <option value="{{ $event->id }}">{{ $event->name }} ({{ $event->event_code }})</option>
              @endforeach
            </select>
            <div class="invalid-feedback" id="add-limit-error-event_id"></div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Nominator</label>
            <select name="nominator_id" class="form-select rounded-3" required>
              <option value="">Select Nominator...</option>
              @foreach($nominators as $nom)
                <option value="{{ $nom->id }}">{{ $nom->name }} {{ $nom->last_name }} ({{ $nom->email }})</option>
              @endforeach
            </select>
            <div class="invalid-feedback" id="add-limit-error-nominator_id"></div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Max Nominees Allowed</label>
            <input type="number" name="max_nominees" class="form-control rounded-3" required min="0" placeholder="e.g. 5">
            <div class="invalid-feedback" id="add-limit-error-max_nominees"></div>
          </div>
          <div class="text-end">
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 me-2" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm btn-primary bg-primary rounded-3">Save Limit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Limit Modal -->
<div class="modal fade" id="editLimitModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header border-0 bg-light py-3">
        <h5 class="modal-title fw-bold">Edit Custom Limit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4">
        <form id="edit-limit-form">
          <input type="hidden" name="id" id="edit-limit-id">
          <div class="mb-3">
            <label class="form-label fw-bold d-block text-secondary">Event</label>
            <span id="edit-limit-event-name" class="fw-semibold"></span>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold d-block text-secondary">Nominator</label>
            <span id="edit-limit-nominator-name" class="fw-semibold"></span>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Max Nominees Allowed</label>
            <input type="number" name="max_nominees" id="edit-limit-max-nominees" class="form-control rounded-3" required min="0">
            <div class="invalid-feedback" id="edit-limit-error-max_nominees"></div>
          </div>
          <div class="text-end">
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 me-2" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm btn-primary bg-primary rounded-3">Update Limit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteLimitConfirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header border-0 bg-danger bg-opacity-10 py-3">
        <h5 class="modal-title fw-bold text-danger">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4 text-center">
        <p class="mb-4">Are you sure you want to delete this custom nominator limit?</p>
        <div class="d-flex justify-content-center gap-2">
          <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 px-3" data-bs-dismiss="modal">Cancel</button>
          <button type="button" id="confirm-delete-limit-btn" class="btn btn-sm btn-danger rounded-3 px-3">Delete</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// Global Instances of Bootstrap Modals
const limitsModals = {
    addLimit: new bootstrap.Modal(document.getElementById('addLimitModal')),
    editLimit: new bootstrap.Modal(document.getElementById('editLimitModal')),
    deleteLimit: new bootstrap.Modal(document.getElementById('deleteLimitConfirmModal'))
};

// Common Headers for AJAX
const requestHeaders = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-CSRF-TOKEN': '{{ csrf_token() }}'
};

function filterLimitsTable() {
    const searchVal = document.getElementById('search-limits').value.toLowerCase();
    const rows = document.querySelectorAll('#limits-table tbody tr');
    
    rows.forEach(row => {
        const eventCell = row.querySelector('.event-name');
        if (!eventCell) return; // empty row case
        
        const eventText = eventCell.textContent.toLowerCase();
        const nomNameText = row.querySelector('.nominator-name').textContent.toLowerCase();
        const nomEmailText = row.querySelector('.nominator-email').textContent.toLowerCase();
        
        if (eventText.includes(searchVal) || nomNameText.includes(searchVal) || nomEmailText.includes(searchVal)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function openAddLimitModal() {
    clearValidationErrors('add-limit-form');
    document.getElementById('add-limit-form').reset();
    limitsModals.addLimit.show();
}

document.getElementById('add-limit-form').addEventListener('submit', function(e) {
    e.preventDefault();
    clearValidationErrors('add-limit-form');
    const formData = new FormData(this);
    
    fetch("{{ route('admin-nominator-limits-store') }}", {
        method: 'POST',
        headers: requestHeaders,
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(async res => {
        const data = await res.json();
        if (res.ok) {
            limitsModals.addLimit.hide();
            showToast(data.message || "Custom limit saved successfully.");
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            handleValidationErrors('add-limit-form', data.errors || { max_nominees: [data.message] });
        }
    })
    .catch(() => showToast("Failed to save custom limit.", 'bg-danger'));
});

function openEditLimitModal(id, eventName, nominatorName, maxNominees) {
    clearValidationErrors('edit-limit-form');
    document.getElementById('edit-limit-id').value = id;
    document.getElementById('edit-limit-event-name').textContent = eventName;
    document.getElementById('edit-limit-nominator-name').textContent = nominatorName;
    document.getElementById('edit-limit-max-nominees').value = maxNominees;
    limitsModals.editLimit.show();
}

document.getElementById('edit-limit-form').addEventListener('submit', function(e) {
    e.preventDefault();
    clearValidationErrors('edit-limit-form');
    const id = document.getElementById('edit-limit-id').value;
    const formData = new FormData(this);
    
    fetch(`/admin/nominator-limits/${id}/update`, {
        method: 'POST',
        headers: requestHeaders,
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(async res => {
        const data = await res.json();
        if (res.ok) {
            limitsModals.editLimit.hide();
            showToast(data.message || "Custom limit updated successfully.");
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            handleValidationErrors('edit-limit-form', data.errors);
        }
    })
    .catch(() => showToast("Failed to update custom limit.", 'bg-danger'));
});

let limitIdToDelete = null;

function deleteLimit(id) {
    limitIdToDelete = id;
    limitsModals.deleteLimit.show();
}

document.getElementById('confirm-delete-limit-btn').addEventListener('click', function() {
    if (limitIdToDelete) {
        limitsModals.deleteLimit.hide();
        fetch(`/admin/nominator-limits/${limitIdToDelete}/delete`, {
            method: 'POST',
            headers: requestHeaders
        })
        .then(res => res.json())
        .then(data => {
            showToast(data.message || "Custom limit deleted successfully.");
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        })
        .catch(() => showToast("Failed to delete custom limit.", 'bg-danger'));
    }
});

// Error Helpers
function handleValidationErrors(formId, errors) {
    if (!errors) return;
    const form = document.getElementById(formId);
    Object.keys(errors).forEach(field => {
        const input = form.querySelector(`[name="${field}"]`);
        if (input) {
            input.classList.add('is-invalid');
            const feedback = form.querySelector(`#${formId}-error-${field}`);
            if (feedback) {
                feedback.textContent = errors[field].join(' ');
            }
        }
    });
}

function clearValidationErrors(formId) {
    const form = document.getElementById(formId);
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
}
</script>
@endpush
