@extends('layouts.app')

@section('content')
  <!-- Success Notification -->
  @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
          <div class="d-flex align-items-center">
              <i class="bi bi-check-circle-fill me-2 fs-5"></i>
              <div>{{ session('success') }}</div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
  @endif

  <!-- General Validation Errors Alert -->
  @if ($errors->any())
      <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
          <div class="d-flex align-items-start">
              <i class="bi bi-exclamation-triangle-fill me-2 fs-5 mt-1"></i>
              <div>
                  <strong class="d-block mb-1">Please correct the validation errors listed below:</strong>
                  <ul class="mb-0 ps-3">
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          </div>
      </div>
  @endif

  <!-- Event Creation Form Section -->
  <div id="event-creation-form" class="border rounded-4 p-4 mb-4 bg-light">
    <h5 class="fw-bold mb-3">Create New Event</h5>
    <form id="new-event-form" method="post" action="{{ route('admin-save-new-event') }}">
      @csrf
      <div class="row g-3">
        <!-- Event Code -->
        <div class="col-md-6">
          <label class="form-label fw-bold small" for="form-event-code">Event Code</label>
          <input type="text" name="event_code" value="{{ old('event_code') }}" class="form-control form-control-sm @error('event_code') is-invalid @enderror" required id="form-event-code" placeholder="e.g. EVT-2026-001" />
          @error('event_code')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <!-- Event Name -->
        <div class="col-md-6">
          <label class="form-label fw-bold small" for="form-event-name">Event Name</label>
          <input type="text" name="name" value="{{ old('name') }}" class="form-control form-control-sm @error('name') is-invalid @enderror" required id="form-event-name" placeholder="e.g. Annual Summit" />
          @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <!-- Event Start Date -->
        <div class="col-md-6">
          <label class="form-label fw-bold small" for="form-event-start-date">Event Start Date</label>
          <input type="date" name="start_date" value="{{ old('start_date') }}" class="form-control form-control-sm @error('start_date') is-invalid @enderror" required id="form-event-start-date" />
          @error('start_date')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <!-- Event End Date -->
        <div class="col-md-6">
          <label class="form-label fw-bold small" for="form-event-end-date">Event End Date</label>
          <input type="date" name="end_date" value="{{ old('end_date') }}" class="form-control form-control-sm @error('end_date') is-invalid @enderror" required id="form-event-end-date" />
          @error('end_date')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <!-- Event Location -->
        <div class="col-md-6">
          <label class="form-label fw-bold small" for="form-event-location">Event Location</label>
          <input type="text" name="location" value="{{ old('location') }}" class="form-control form-control-sm @error('location') is-invalid @enderror" required id="form-event-location" placeholder="e.g. San Francisco, CA" />
          @error('location')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <!-- Event Type -->
        <div class="col-md-6">
          <label class="form-label fw-bold small" for="form-event-type">Event Type</label>
          <select name="type" class="form-select form-select-sm @error('type') is-invalid @enderror" required id="form-event-type" onchange="toggleHospitalityFields()">
            <option value="Hospitality" {{ old('type', 'Hospitality') === 'Hospitality' ? 'selected' : '' }}>Hospitality</option>
            <option value="Non-Hospitality" {{ old('type') === 'Non-Hospitality' ? 'selected' : '' }}>Non-Hospitality</option>
          </select>
          @error('type')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <!-- Event Scope -->
        <div class="col-md-6">
          <label class="form-label fw-bold small" for="form-event-scope">Event Scope</label>
          <select name="scope" class="form-select form-select-sm @error('scope') is-invalid @enderror" required id="form-event-scope">
            <option value="internal" {{ old('scope') === 'internal' ? 'selected' : '' }}>Internal</option>
            <option value="external" {{ old('scope', 'external') === 'external' ? 'selected' : '' }}>External</option>
          </select>
          @error('scope')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <!-- Event Nomination Deadline -->
        <div class="col-md-6">
          <label class="form-label fw-bold small" for="form-event-deadline">Event Nomination Deadline</label>
          <input type="datetime-local" name="nomination_deadline" value="{{ old('nomination_deadline') }}" class="form-control form-control-sm @error('nomination_deadline') is-invalid @enderror" required id="form-event-deadline" />
          @error('nomination_deadline')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <!-- Event Nomination Limit -->
        <div class="col-md-6">
          <label class="form-label fw-bold small" for="form-event-limit">Event Nomination Limit</label>
          <input type="number" name="nomination_limit" value="{{ old('nomination_limit', 10) }}" class="form-control form-control-sm @error('nomination_limit') is-invalid @enderror" required id="form-event-limit" min="1" />
          @error('nomination_limit')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <!-- GDPR Compliance (Default Field) -->
        <div class="col-md-6">
          <label class="form-label fw-bold small" for="form-event-gdpr">GDPR Compliance</label>
          <select name="gdpr_compliance" class="form-select form-select-sm @error('gdpr_compliance') is-invalid @enderror" required id="form-event-gdpr">
            @foreach($gdprOptions as $option)
              <option value="{{ $option->name }}" {{ old('gdpr_compliance') === $option->name ? 'selected' : '' }}>{{ $option->name }}</option>
            @endforeach
          </select>
          @error('gdpr_compliance')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Conditional Hospitality Fields Container -->
        <div id="hospitality-fields-container" class="col-12 border rounded p-3 bg-white mt-3 shadow-sm animate-fade-in">
          <p class="fw-bold text-orange mb-3"><i class="bi bi-gift-fill me-1"></i> Hospitality Details</p>
          <div class="row g-3">
            <!-- Declaration -->
            <div class="col-md-6">
              <label class="form-label fw-bold small" for="form-event-declaration">Declaration</label>
              <input type="text" name="declaration" value="{{ old('declaration') }}" class="form-control form-control-sm @error('declaration') is-invalid @enderror" id="form-event-declaration" placeholder="Enter declaration text" />
              @error('declaration')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <!-- Invite For -->
            <div class="col-md-6">
              <label class="form-label fw-bold small" for="form-event-invite-for">Invite For</label>
              <input type="text" name="invite_for" value="{{ old('invite_for') }}" class="form-control form-control-sm @error('invite_for') is-invalid @enderror" id="form-event-invite-for" placeholder="e.g. Both Men's Finals" />
              @error('invite_for')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <!-- Invite Spouser -->
            <div class="col-md-6">
              <label class="form-label fw-bold small" for="form-event-invite-spouser">Invite Spouser</label>
              <input type="text" name="invite_spouser" value="{{ old('invite_spouser') }}" class="form-control form-control-sm @error('invite_spouser') is-invalid @enderror" id="form-event-invite-spouser" placeholder="Spouse details / allowance details" />
              @error('invite_spouser')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <!-- Govt/State Owned Company -->
            <div class="col-md-6">
              <label class="form-label fw-bold small" for="form-event-govt-company">Govt/State Owned Company</label>
              <input type="text" name="govt_company" value="{{ old('govt_company') }}" class="form-control form-control-sm @error('govt_company') is-invalid @enderror" id="form-event-govt-company" placeholder="Govt/State Owned Company details" />
              @error('govt_company')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>

        <!-- Submit & Cancel Buttons -->
        <div class="col-12 text-end mt-4">
          <a href="{{ route('dashboard') }}#events" class="btn btn-sm btn-outline-secondary me-2">Cancel</a>
          <button type="submit" class="btn btn-sm btn-primary bg-primary">Create Event</button>
        </div>
      </div>
    </form>
  </div>
@endsection

@push('scripts')
<script>
function toggleHospitalityFields() {
    const type = document.getElementById('form-event-type').value;
    const container = document.getElementById('hospitality-fields-container');
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

// Call on load to initialize correctly (especially if validation errors occurred and old input was loaded)
document.addEventListener('DOMContentLoaded', () => {
    toggleHospitalityFields();
});
</script>
@endpush