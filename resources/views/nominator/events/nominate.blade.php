@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('nominator-submit-nomination', $event->id) }}">
  @csrf
  <h2 class="mb-0">Apply Nomination - {{ $event->name }}</h2>

  <!-- General Validation Errors Alert -->
  @if ($errors->any())
      <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 mt-3">
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

  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
    <p class="text-secondary">
      Submit a new candidate for the current nomination cycle of <strong>{{ $event->name }}</strong>.
    </p>

    <div class="d-flex flex-column flex-sm-row gap-3">
      <a href="{{ route('nominator-active-events') }}" class="btn btn-secondary bg-secondary border-0 text-black text-decoration-none d-flex align-items-center justify-content-center px-3">
        Cancel
      </a>
      <button
        type="submit"
        class="btn btn-primary bg-primary text-white border-0"
      >
        Submit Nomination
      </button>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">
      <!-- Left side -->
      <div class="col-12 col-lg-8 p-0">
        <div class="border p-3 rounded-4 mb-3">
          <div class="row row-gap-3">
            <!-- GDPR Compliance -->
            <div class="col-12 col-md-6">
              <label class="form-label">
                GDPR Compliance
                <span class="text-danger">*</span>
              </label>

              <select name="gdpr_compliance" class="form-select shadow-none rounded-3" required>
                <option value="" disabled selected>Select</option>
                <option value="Existing Business Relationship (Client)" {{ old('gdpr_compliance') === 'Existing Business Relationship (Client)' ? 'selected' : '' }}>
                  Existing Business Relationship (Client)
                </option>
                <option value="Legitimate Business Interest(Prospect)" {{ old('gdpr_compliance') === 'Legitimate Business Interest(Prospect)' ? 'selected' : '' }}>
                  Legitimate Business Interest(Prospect)
                </option>
              </select>
            </div>

            <!-- Units -->
            <div class="col-12 col-md-6">
              <div class="row row-gap-3">
                <div class="col-12 col-md-6">
                  <label class="form-label">
                    Unit
                    <span class="text-danger">*</span>
                  </label>

                  <select name="unit" class="form-select shadow-none rounded-3" required>
                    <option value="" disabled selected>Select</option>
                    <option value="FS" {{ old('unit') === 'FS' ? 'selected' : '' }}>FS</option>
                    <option value="SURE" {{ old('unit') === 'SURE' ? 'selected' : '' }}>SURE</option>
                    <option value="RETAIL" {{ old('unit') === 'RETAIL' ? 'selected' : '' }}>RETAIL</option>
                  </select>
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label">
                    Sub Unit
                    <span class="text-danger">*</span>
                  </label>

                  <select name="sub_unit" class="form-select shadow-none rounded-3" required>
                    <option value="" disabled selected>Select</option>
                    <option value="FSIB" {{ old('sub_unit') === 'FSIB' ? 'selected' : '' }}>FSIB</option>
                    <option value="SURE-R" {{ old('sub_unit') === 'SURE-R' ? 'selected' : '' }}>SURE-R</option>
                    <option value="RET-EU" {{ old('sub_unit') === 'RET-EU' ? 'selected' : '' }}>RET-EU</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- First name -->
            <div class="col-12 col-sm-6">
              <label class="form-label">
                First Name
                <span class="text-danger">*</span>
              </label>

              <input
                type="text"
                name="first_name"
                value="{{ old('first_name') }}"
                class="form-control"
                required
                placeholder="John"
              />
            </div>

            <!-- Last Name -->
            <div class="col-12 col-sm-6">
              <label class="form-label">
                Last Name
                <span class="text-danger">*</span>
              </label>

              <input
                type="text"
                name="last_name"
                value="{{ old('last_name') }}"
                class="form-control"
                required
                placeholder="Doe"
              />
            </div>

            <!-- Email Address -->
            <div class="col-12 col-sm-6">
              <label class="form-label">
                Email Address
                <span class="text-danger">*</span>
              </label>

              <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                class="form-control"
                required
                placeholder="john.doe@example.com"
              />
            </div>

            <!-- Company -->
            <div class="col-12 col-sm-6">
              <label class="form-label">
                Company
                <span class="text-danger">*</span>
              </label>

              <input
                type="text"
                name="company"
                value="{{ old('company') }}"
                class="form-control"
                required
                placeholder="Infosys"
              />
            </div>

            <!-- Title -->
            <div class="col-12 col-sm-6">
              <label class="form-label">
                Title
                <span class="text-danger">*</span>
              </label>

              <input
                type="text"
                name="title"
                value="{{ old('title') }}"
                class="form-control"
                required
                placeholder="Fullstack Developer"
              />
            </div>

            <!-- Job Level -->
            <div class="col-12 col-md-6">
              <label class="form-label">
                Job Level
                <span class="text-danger">*</span>
              </label>

              <select name="job_level" class="form-select shadow-none rounded-3" required>
                <option value="" disabled selected>Select</option>
                <option value="1" {{ old('job_level') === '1' ? 'selected' : '' }}>Level 1</option>
                <option value="2" {{ old('job_level') === '2' ? 'selected' : '' }}>Level 2</option>
                <option value="3" {{ old('job_level') === '3' ? 'selected' : '' }}>Level 3</option>
                <option value="4" {{ old('job_level') === '4' ? 'selected' : '' }}>Level 4</option>
              </select>
            </div>

            <!-- Primary account manager name -->
            <div class="col-12 col-sm-6">
              <label class="form-label">
                Primary Account Manager Name
                <span class="text-danger">*</span>
              </label>

              <input
                type="text"
                name="primary_account_manager_name"
                value="{{ old('primary_account_manager_name') }}"
                class="form-control"
                required
                placeholder="Amanda Smith"
              />
            </div>

            <!-- Primary account manager email id -->
            <div class="col-12 col-sm-6">
              <label class="form-label">
                Primary Account Manager Email ID
                <span class="text-danger">*</span>
              </label>

              <input
                type="email"
                name="primary_account_manager_email"
                value="{{ old('primary_account_manager_email') }}"
                class="form-control"
                required
                placeholder="a.smith@infosys.com"
              />
            </div>

            <!-- Account manager email id -->
            <div class="col-12 col-sm-6">
              <label class="form-label">
                Account Manager Email ID
              </label>

              <input
                type="email"
                name="account_manager_email_1"
                value="{{ old('account_manager_email_1') }}"
                class="form-control"
                placeholder="manager1@infosys.com"
              />
            </div>

            <!-- Account manager email id 2 -->
            <div class="col-12 col-sm-6">
              <label class="form-label">
                Account Manager Email ID 2
              </label>

              <input
                type="email"
                name="account_manager_email_2"
                value="{{ old('account_manager_email_2') }}"
                class="form-control"
                placeholder="manager2@infosys.com"
              />
            </div>

            <!-- Business / IT -->
            <div class="col-12 col-sm-6">
              <label class="form-label">
                Business / IT
                <span class="text-danger">*</span>
              </label>

              <select name="business_or_it" class="form-select shadow-none rounded-3" required>
                <option value="" disabled selected>Select</option>
                <option value="Business" {{ old('business_or_it') === 'Business' ? 'selected' : '' }}>Business</option>
                <option value="IT" {{ old('business_or_it') === 'IT' ? 'selected' : '' }}>IT</option>
              </select>
            </div>

            <!-- Country -->
            <div class="col-12 col-sm-6">
              <label class="form-label">
                Country
                <span class="text-danger">*</span>
              </label>

              <input
                type="text"
                name="country"
                value="{{ old('country') }}"
                class="form-control"
                required
                placeholder="India"
              />
            </div>
          </div>
        </div>
      </div>

      <!-- Right side -->
      <div class="col-12 col-lg-4 px-0 ps-lg-3">
        <div class="border p-3 rounded-4 mb-3">
          <div class="d-flex align-items-center gap-2 mb-3">
            <div class="icon-with-bg icon-bg-light-blue rounded-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-plus-lg text-primary"></i>
            </div>

            <div class="d-flex flex-column text-start">
              <h3 class="card-title mb-0">Bulk Nominations</h3>

              <p class="text-secondary text-small lh-1 fw-light mb-0">
                Nominate multiple candidates by uploading a sheet.
              </p>
            </div>
          </div>

          <!-- Download form -->
          <div class="border-light-grey p-3 rounded-4 bg-light-sky-blue mb-3">
            <p class="pb-3 text-secondary mb-0">
              Ensure your CSV or Excel file matches our required schema before downloading the template.
            </p>

            <button type="button" class="btn bg-white border w-100" onclick="alert('Downloading form template...')">
              <i class="bi bi-download me-2"></i> Download Form
            </button>
          </div>

          <!-- Upload form -->
          <div class="border-light-grey p-3 rounded-4 bg-light-sky-blue">
            <p class="pb-3 text-secondary mb-0">
              While uploading, ensure your file matches our required schema.
            </p>

            <button type="button" class="btn btn-primary bg-primary border w-100" onclick="alert('Please use the upload option on the Active Events page.')">
              <i class="bi bi-upload me-2"></i> Upload CSV
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
@endsection
