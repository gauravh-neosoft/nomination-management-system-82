@extends('layouts.app')

@section('content')
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

  <!-- User Creation Form Section -->
  <div id="user-creation-form" class="border rounded-4 p-4 mb-4 bg-white shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
      <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-person-plus-fill text-primary me-2"></i>Create New User</h4>
        <p class="text-secondary small mb-0">Register a new profile and configure their access role inside the system.</p>
      </div>
    </div>

    <form id="new-user-form" method="post" action="{{ route('admin-users-store') }}">
      @csrf
      <div class="row g-3">
        <!-- First Name -->
        <div class="col-md-6">
          <label class="form-label fw-bold small" for="form-user-name">First Name</label>
          <input type="text" name="name" value="{{ old('name') }}" class="form-control form-control-sm @error('name') is-invalid @enderror" required id="form-user-name" placeholder="e.g. Jane" />
          @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Last Name -->
        <div class="col-md-6">
          <label class="form-label fw-bold small" for="form-user-last-name">Last Name</label>
          <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control form-control-sm @error('last_name') is-invalid @enderror" required id="form-user-last-name" placeholder="e.g. Miller" />
          @error('last_name')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Email Address -->
        <div class="col-md-6">
          <label class="form-label fw-bold small" for="form-user-email">Email Address</label>
          <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-sm @error('email') is-invalid @enderror" required id="form-user-email" placeholder="e.g. jane.miller@company.com" />
          @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Password -->
        <div class="col-md-6">
          <label class="form-label fw-bold small" for="form-user-password">Password</label>
          <input type="password" name="password" class="form-control form-control-sm @error('password') is-invalid @enderror" required id="form-user-password" placeholder="At least 8 characters" />
          @error('password')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Contact Number -->
        <div class="col-md-6">
          <label class="form-label fw-bold small" for="form-user-contact">Contact Number</label>
          <input type="text" name="contact_no" value="{{ old('contact_no') }}" class="form-control form-control-sm @error('contact_no') is-invalid @enderror" id="form-user-contact" placeholder="e.g. +1234567890" />
          @error('contact_no')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Role Select -->
        <div class="col-md-6">
          <label class="form-label fw-bold small" for="form-user-role">Role / Access Level</label>
          <select name="role_id" class="form-select form-select-sm @error('role_id') is-invalid @enderror" required id="form-user-role">
            <option value="">Select Role...</option>
            @foreach($roles as $role)
              <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
            @endforeach
          </select>
          @error('role_id')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- SSO ID -->
        <div class="col-md-6">
          <label class="form-label fw-bold small" for="form-user-sso">SSO ID</label>
          <input type="text" name="sso_id" value="{{ old('sso_id') }}" class="form-control form-control-sm @error('sso_id') is-invalid @enderror" id="form-user-sso" placeholder="e.g. sso_12345" />
          @error('sso_id')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Status Select -->
        <div class="col-md-6">
          <label class="form-label fw-bold small" for="form-user-status">Status</label>
          <select name="status" class="form-select form-select-sm @error('status') is-invalid @enderror" required id="form-user-status">
            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
          </select>
          @error('status')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <!-- Submit & Cancel Buttons -->
        <div class="col-12 text-end mt-4 border-top pt-3">
          <a href="{{ route('admin-users') }}" class="btn btn-sm btn-outline-secondary me-2">Cancel</a>
          <button type="submit" class="btn btn-sm btn-primary bg-primary">Create User</button>
        </div>
      </div>
    </form>
  </div>
@endsection
