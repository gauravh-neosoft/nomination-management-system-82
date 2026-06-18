@extends('layouts.app')

@section('content')
<h2 class="mb-0">My Profile</h2>
<p class="text-secondary">
  View and manage your professional credentials and contact details.
</p>

<div class="container-fluid my-3">
  <div class="row">
    <!-- Left side -->
    <div class="col-12 col-lg-4 p-0 px-lg-3 mb-3 mb-lg-0">
      <div class="border rounded-4 p-4 d-flex flex-column">
        <div class="d-flex flex-column align-items-center border-bottom pb-3 flex-grow-1">
          <img
            src="{{ asset('assets/images/profile.svg') }}"
            height="100"
            alt="Profile Picture"
          />

          <h3 class="mb-0 text-center">{{ $user->name }}</h3>
          <p class="text-muted text-center">Senior Nomination Strategist</p>
          <p class="fw-semibold text-muted text-center">{{ $roleName }}</p>
        </div>

        <div class="py-3 d-flex justify-content-center justify-content-sm-between flex-wrap gap-2">
          <div class="text-center">
            <h4 class="stat-value fw-bold">{{ $nominationsCount }}</h4>
            <p class="fw-semibold text-muted text-small">
              NOMINATIONS
            </p>
          </div>
          <div class="text-center">
            <h4 class="stat-value fw-bold">{{ $approvedCount }}</h4>
            <p class="fw-semibold text-muted text-small">
              APPROVED
            </p>
          </div>
          <div class="text-center">
            <h4 class="stat-value fw-bold">{{ $pendingCount }}</h4>
            <p class="fw-semibold text-muted text-small">
              PENDING
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Right side -->
    <div class="col-12 col-lg-8 border rounded-4 p-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Personal Information</h3>
        <p class="mb-0 fw-light">Read-only</p>
      </div>

      <div class="row">
        <div class="col-12 col-md-6">
          <div class="mb-3">
            <label class="form-label text-secondary">
              Full Name
              <span class="text-danger">*</span>
            </label>
            <div class="position-relative">
              <i
                class="bi bi-person position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary text-primary"
                aria-hidden="true"
              ></i>
              <input
                type="text"
                class="form-control ps-5 shadow-none rounded-3"
                value="{{ $user->name }}"
                readonly
              />
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="mb-3">
            <label class="form-label text-secondary">
              Email Address
              <span class="text-danger">*</span>
            </label>
            <div class="position-relative">
              <i
                class="bi bi-envelope position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary text-primary"
                aria-hidden="true"
              ></i>
              <input
                type="text"
                class="form-control ps-5 shadow-none rounded-3"
                value="{{ $user->email }}"
                readonly
              />
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="mb-3">
            <label class="form-label text-secondary">
              Contact Number
              <span class="text-danger">*</span>
            </label>
            <div class="position-relative">
              <i
                class="bi bi-telephone position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary text-primary"
                aria-hidden="true"
              ></i>
              <input
                type="text"
                class="form-control ps-5 shadow-none rounded-3"
                value="+91 9370387079"
                readonly
              />
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="mb-3">
            <label class="form-label text-secondary">
              Department
              <span class="text-danger">*</span>
            </label>
            <div class="position-relative">
              <i
                class="bi bi-building position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary text-primary"
                aria-hidden="true"
              ></i>
              <input
                type="text"
                class="form-control ps-5 shadow-none rounded-3"
                value="Marketing"
                readonly
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
