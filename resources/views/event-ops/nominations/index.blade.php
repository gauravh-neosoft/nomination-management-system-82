@extends('layouts.app')

@section('content')
<h2 class="mb-0">Nominations</h2>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
  <p class="text-secondary mb-0">View and manage all nominations at one place.</p>
</div>

<!-- Filters -->
<div class="border rounded-3 p-2 my-3 bg-white">
  <form action="{{ route('event-ops-nominations') }}" method="GET" class="d-flex align-items-center gap-3">
    <!-- Search -->
    <div class="flex-grow-1">
      <div class="d-flex align-items-center">
        <i class="bi bi-search text-secondary me-2"></i>
        <input
          type="text"
          name="search"
          value="{{ $search }}"
          class="form-control border-0 shadow-none p-0"
          placeholder="Search by nominee name or event name.."
          onchange="this.form.submit()"
        />
      </div>
    </div>
    <button type="submit" class="btn btn-sm btn-primary">Search</button>
  </form>
</div>
<!-- Filters End -->

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<div class="border rounded-3 p-2">
  <div class="underline-tabs">
    <div class="d-flex align-items-center justify-content-between flex-wrap border-bottom pb-2 mb-3">
      <ul class="nav nav-tabs border-0 mb-0" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active text-black" data-bs-toggle="tab" data-bs-target="#nominations_pending" type="button" role="tab">
            Nomination Pending
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link text-black" data-bs-toggle="tab" data-bs-target="#nominations_reviewed" type="button" role="tab">
            Nominations Reviewed
          </button>
        </li>
      </ul>
      <div class="d-flex align-items-center gap-3 mt-2 mt-md-0">
        <p class="mb-0 fw-light text-secondary">{{ $pendingNominations->count() + $reviewedNominations->count() }} Total Rows</p>
      </div>
    </div>

    <div class="tab-content">
      <!-- Pending Tab -->
      <div class="tab-pane fade show active" id="nominations_pending" role="tabpanel">
        <div class="table-responsive">
          <table class="table table-borderless align-middle">
            <thead>
              <tr class="table-light">
                <th scope="col" style="min-width: 70px" class="text-center">Serial No.</th>
                <th scope="col" style="min-width: 150px" class="text-center">Invite Status</th>
                <th scope="col" style="min-width: 150px" class="text-center">Action</th>
                <th scope="col" style="min-width: 150px">Nominator</th>
                <th scope="col" style="min-width: 150px">GDPR Compliant</th>
                <th scope="col" style="min-width: 200px">Event Name</th>
                <th scope="col" style="min-width: 120px">Unit</th>
                <th scope="col" style="min-width: 120px">Sub Unit</th>
                <th scope="col" style="min-width: 200px">Name</th>
                <th scope="col" style="min-width: 200px">Email</th>
                <th scope="col" style="min-width: 150px" class="text-center">Company</th>
                <th scope="col" style="min-width: 120px">Job Title</th>
              </tr>
            </thead>
            <tbody>
              @forelse($pendingNominations as $index => $nominee)
                <tr class="{{ $index % 2 == 1 ? 'table-light' : '' }}">
                  <td class="text-center">{{ $index + 1 }}</td>
                  <td class="text-center">
                    <span class="badge bg-light-yellow text-warning rounded-pill fw-normal px-2 py-1">Pending</span>
                  </td>
                  <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                      <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editNominationModal-{{ $nominee->id }}">Edit</button>
                      <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addRemarkModal-{{ $nominee->id }}">Remark</button>
                    </div>
                  </td>
                  <td>{{ $nominee->nominator_name }}</td>
                  <td>{{ $nominee->gdpr_compliance }}</td>
                  <td>{{ $nominee->event_name }}</td>
                  <td>{{ $nominee->unit }}</td>
                  <td>{{ $nominee->sub_unit }}</td>
                  <td>{{ $nominee->full_name }}</td>
                  <td>{{ $nominee->email }}</td>
                  <td class="text-center">{{ $nominee->company }}</td>
                  <td>{{ $nominee->title }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="12" class="text-center text-muted py-4">No pending nominations found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Reviewed Tab -->
      <div class="tab-pane fade" id="nominations_reviewed" role="tabpanel">
        <div class="table-responsive">
          <table class="table table-borderless align-middle">
            <thead>
              <tr class="table-light">
                <th scope="col" style="min-width: 70px" class="text-center">Serial No.</th>
                <th scope="col" style="min-width: 150px" class="text-center">Invite Status</th>
                <th scope="col" style="min-width: 150px" class="text-center">Action</th>
                <th scope="col" style="min-width: 150px">Nominator</th>
                <th scope="col" style="min-width: 150px">GDPR Compliant</th>
                <th scope="col" style="min-width: 200px">Event Name</th>
                <th scope="col" style="min-width: 120px">Unit</th>
                <th scope="col" style="min-width: 120px">Sub Unit</th>
                <th scope="col" style="min-width: 200px">Name</th>
                <th scope="col" style="min-width: 200px">Email</th>
                <th scope="col" style="min-width: 150px" class="text-center">Company</th>
                <th scope="col" style="min-width: 120px">Job Title</th>
              </tr>
            </thead>
            <tbody>
              @forelse($reviewedNominations as $index => $nominee)
                <tr class="{{ $index % 2 == 1 ? 'table-light' : '' }}">
                  <td class="text-center">{{ $index + 1 }}</td>
                  <td class="text-center">
                    <span class="badge bg-light-green text-success rounded-pill fw-normal px-2 py-1">{{ $nominee->invite_status }}</span>
                  </td>
                  <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                      <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editNominationModal-{{ $nominee->id }}">Edit</button>
                      <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addRemarkModal-{{ $nominee->id }}">Remark</button>
                    </div>
                  </td>
                  <td>{{ $nominee->nominator_name }}</td>
                  <td>{{ $nominee->gdpr_compliance }}</td>
                  <td>{{ $nominee->event_name }}</td>
                  <td>{{ $nominee->unit }}</td>
                  <td>{{ $nominee->sub_unit }}</td>
                  <td>{{ $nominee->full_name }}</td>
                  <td>{{ $nominee->email }}</td>
                  <td class="text-center">{{ $nominee->company }}</td>
                  <td>{{ $nominee->title }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="12" class="text-center text-muted py-4">No reviewed nominations found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Remark & Edit Modals for Pending Nominations -->
@foreach($pendingNominations as $nominee)
  <!-- Add Remark Modal -->
  <div class="modal fade" id="addRemarkModal-{{ $nominee->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="{{ route('event-ops-add-remark', $nominee->id) }}" method="POST">
          @csrf
          <div class="modal-header border-0">
            <h3 class="modal-title fs-5">Add Remarks - {{ $nominee->full_name }}</h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <textarea name="remark" class="border w-100 rounded-3 p-3" rows="5" placeholder="Enter remarks..">{{ $nominee->spoc_comment }}</textarea>
          </div>
          <div class="modal-footer border-0 pt-0">
            <button type="submit" class="btn btn-primary w-100">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Nomination Modal -->
  <div class="modal fade" id="editNominationModal-{{ $nominee->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <form action="{{ route('event-ops-update-nomination', $nominee->id) }}" method="POST">
          @csrf
          <div class="modal-header justify-content-between">
            <h3 class="modal-title fs-5">Edit Nomination - {{ $nominee->full_name }}</h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="border p-4 rounded-4 mb-3">
              <div class="row row-gap-3">
                <div class="col-12 col-md-6">
                  <label class="form-label">GDPR Compliance <span class="text-danger">*</span></label>
                  <select name="gdpr_compliance" class="form-select shadow-none rounded-3" required>
                    @foreach($gdprOptions as $opt)
                      <option value="{{ $opt }}" {{ $nominee->gdpr_compliance === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                    <option value="Existing Business Relationship (Client)" {{ $nominee->gdpr_compliance === 'Existing Business Relationship (Client)' ? 'selected' : '' }}>Existing Business Relationship (Client)</option>
                    <option value="Legitimate Business Interest(Prospect)" {{ $nominee->gdpr_compliance === 'Legitimate Business Interest(Prospect)' ? 'selected' : '' }}>Legitimate Business Interest(Prospect)</option>
                  </select>
                </div>

                <div class="col-12 col-md-3">
                  <label class="form-label">Unit <span class="text-danger">*</span></label>
                  <input type="text" name="unit" value="{{ $nominee->unit }}" class="form-control rounded-3" required />
                </div>
                <div class="col-12 col-md-3">
                  <label class="form-label">Sub Unit <span class="text-danger">*</span></label>
                  <input type="text" name="sub_unit" value="{{ $nominee->sub_unit }}" class="form-control rounded-3" required />
                </div>

                <div class="col-12 col-sm-6">
                  <label class="form-label">First Name <span class="text-danger">*</span></label>
                  <input type="text" name="first_name" value="{{ $nominee->first_name }}" class="form-control rounded-3" required />
                </div>
                <div class="col-12 col-sm-6">
                  <label class="form-label">Last Name <span class="text-danger">*</span></label>
                  <input type="text" name="last_name" value="{{ $nominee->last_name }}" class="form-control rounded-3" required />
                </div>

                <div class="col-12 col-sm-6">
                  <label class="form-label">Email Address <span class="text-danger">*</span></label>
                  <input type="email" name="email" value="{{ $nominee->email }}" class="form-control rounded-3" required />
                </div>
                <div class="col-12 col-sm-6">
                  <label class="form-label">Company <span class="text-danger">*</span></label>
                  <input type="text" name="company" value="{{ $nominee->company }}" class="form-control rounded-3" required />
                </div>

                <div class="col-12 col-sm-6">
                  <label class="form-label">Job Title <span class="text-danger">*</span></label>
                  <input type="text" name="title" value="{{ $nominee->title }}" class="form-control rounded-3" required />
                </div>
                <div class="col-12 col-sm-6">
                  <label class="form-label">Job Level <span class="text-danger">*</span></label>
                  <select name="job_level" class="form-select shadow-none rounded-3" required>
                    <option value="1" {{ $nominee->job_level === '1' ? 'selected' : '' }}>1</option>
                    <option value="2" {{ $nominee->job_level === '2' ? 'selected' : '' }}>2</option>
                    <option value="3" {{ $nominee->job_level === '3' ? 'selected' : '' }}>3</option>
                    <option value="4" {{ $nominee->job_level === '4' ? 'selected' : '' }}>4</option>
                  </select>
                </div>

                <div class="col-12 col-sm-6">
                  <label class="form-label">Primary Account Manager Name <span class="text-danger">*</span></label>
                  <input type="text" name="primary_account_manager_name" value="{{ $nominee->primary_account_manager_name }}" class="form-control rounded-3" required />
                </div>
                <div class="col-12 col-sm-6">
                  <label class="form-label">Primary Account Manager Email <span class="text-danger">*</span></label>
                  <input type="email" name="primary_account_manager_email" value="{{ $nominee->primary_account_manager_email }}" class="form-control rounded-3" required />
                </div>

                <div class="col-12 col-sm-6">
                  <label class="form-label">Account Manager Email ID 1</label>
                  <input type="email" name="account_manager_email_1" value="{{ $nominee->account_manager_email_1 }}" class="form-control rounded-3" />
                </div>
                <div class="col-12 col-sm-6">
                  <label class="form-label">Account Manager Email ID 2</label>
                  <input type="email" name="account_manager_email_2" value="{{ $nominee->account_manager_email_2 }}" class="form-control rounded-3" />
                </div>

                <div class="col-12 col-sm-6">
                  <label class="form-label">Business / IT <span class="text-danger">*</span></label>
                  <select name="business_or_it" class="form-select shadow-none rounded-3" required>
                    <option value="Business" {{ $nominee->business_or_it === 'Business' ? 'selected' : '' }}>Business</option>
                    <option value="IT" {{ $nominee->business_or_it === 'IT' ? 'selected' : '' }}>IT</option>
                  </select>
                </div>
                <div class="col-12 col-sm-6">
                  <label class="form-label">Country <span class="text-danger">*</span></label>
                  <input type="text" name="country" value="{{ $nominee->country }}" class="form-control rounded-3" required />
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endforeach

<!-- Remark & Edit Modals for Reviewed Nominations -->
@foreach($reviewedNominations as $nominee)
  <!-- Add Remark Modal -->
  <div class="modal fade" id="addRemarkModal-{{ $nominee->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="{{ route('event-ops-add-remark', $nominee->id) }}" method="POST">
          @csrf
          <div class="modal-header border-0">
            <h3 class="modal-title fs-5">Add Remarks - {{ $nominee->full_name }}</h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <textarea name="remark" class="border w-100 rounded-3 p-3" rows="5" placeholder="Enter remarks..">{{ $nominee->spoc_comment }}</textarea>
          </div>
          <div class="modal-footer border-0 pt-0">
            <button type="submit" class="btn btn-primary w-100">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Nomination Modal -->
  <div class="modal fade" id="editNominationModal-{{ $nominee->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <form action="{{ route('event-ops-update-nomination', $nominee->id) }}" method="POST">
          @csrf
          <div class="modal-header justify-content-between">
            <h3 class="modal-title fs-5">Edit Nomination - {{ $nominee->full_name }}</h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="border p-4 rounded-4 mb-3">
              <div class="row row-gap-3">
                <div class="col-12 col-md-6">
                  <label class="form-label">GDPR Compliance <span class="text-danger">*</span></label>
                  <select name="gdpr_compliance" class="form-select shadow-none rounded-3" required>
                    @foreach($gdprOptions as $opt)
                      <option value="{{ $opt }}" {{ $nominee->gdpr_compliance === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                    <option value="Existing Business Relationship (Client)" {{ $nominee->gdpr_compliance === 'Existing Business Relationship (Client)' ? 'selected' : '' }}>Existing Business Relationship (Client)</option>
                    <option value="Legitimate Business Interest(Prospect)" {{ $nominee->gdpr_compliance === 'Legitimate Business Interest(Prospect)' ? 'selected' : '' }}>Legitimate Business Interest(Prospect)</option>
                  </select>
                </div>

                <div class="col-12 col-md-3">
                  <label class="form-label">Unit <span class="text-danger">*</span></label>
                  <input type="text" name="unit" value="{{ $nominee->unit }}" class="form-control rounded-3" required />
                </div>
                <div class="col-12 col-md-3">
                  <label class="form-label">Sub Unit <span class="text-danger">*</span></label>
                  <input type="text" name="sub_unit" value="{{ $nominee->sub_unit }}" class="form-control rounded-3" required />
                </div>

                <div class="col-12 col-sm-6">
                  <label class="form-label">First Name <span class="text-danger">*</span></label>
                  <input type="text" name="first_name" value="{{ $nominee->first_name }}" class="form-control rounded-3" required />
                </div>
                <div class="col-12 col-sm-6">
                  <label class="form-label">Last Name <span class="text-danger">*</span></label>
                  <input type="text" name="last_name" value="{{ $nominee->last_name }}" class="form-control rounded-3" required />
                </div>

                <div class="col-12 col-sm-6">
                  <label class="form-label">Email Address <span class="text-danger">*</span></label>
                  <input type="email" name="email" value="{{ $nominee->email }}" class="form-control rounded-3" required />
                </div>
                <div class="col-12 col-sm-6">
                  <label class="form-label">Company <span class="text-danger">*</span></label>
                  <input type="text" name="company" value="{{ $nominee->company }}" class="form-control rounded-3" required />
                </div>

                <div class="col-12 col-sm-6">
                  <label class="form-label">Job Title <span class="text-danger">*</span></label>
                  <input type="text" name="title" value="{{ $nominee->title }}" class="form-control rounded-3" required />
                </div>
                <div class="col-12 col-sm-6">
                  <label class="form-label">Job Level <span class="text-danger">*</span></label>
                  <select name="job_level" class="form-select shadow-none rounded-3" required>
                    <option value="1" {{ $nominee->job_level === '1' ? 'selected' : '' }}>1</option>
                    <option value="2" {{ $nominee->job_level === '2' ? 'selected' : '' }}>2</option>
                    <option value="3" {{ $nominee->job_level === '3' ? 'selected' : '' }}>3</option>
                    <option value="4" {{ $nominee->job_level === '4' ? 'selected' : '' }}>4</option>
                  </select>
                </div>

                <div class="col-12 col-sm-6">
                  <label class="form-label">Primary Account Manager Name <span class="text-danger">*</span></label>
                  <input type="text" name="primary_account_manager_name" value="{{ $nominee->primary_account_manager_name }}" class="form-control rounded-3" required />
                </div>
                <div class="col-12 col-sm-6">
                  <label class="form-label">Primary Account Manager Email <span class="text-danger">*</span></label>
                  <input type="email" name="primary_account_manager_email" value="{{ $nominee->primary_account_manager_email }}" class="form-control rounded-3" required />
                </div>

                <div class="col-12 col-sm-6">
                  <label class="form-label">Account Manager Email ID 1</label>
                  <input type="email" name="account_manager_email_1" value="{{ $nominee->account_manager_email_1 }}" class="form-control rounded-3" />
                </div>
                <div class="col-12 col-sm-6">
                  <label class="form-label">Account Manager Email ID 2</label>
                  <input type="email" name="account_manager_email_2" value="{{ $nominee->account_manager_email_2 }}" class="form-control rounded-3" />
                </div>

                <div class="col-12 col-sm-6">
                  <label class="form-label">Business / IT <span class="text-danger">*</span></label>
                  <select name="business_or_it" class="form-select shadow-none rounded-3" required>
                    <option value="Business" {{ $nominee->business_or_it === 'Business' ? 'selected' : '' }}>Business</option>
                    <option value="IT" {{ $nominee->business_or_it === 'IT' ? 'selected' : '' }}>IT</option>
                  </select>
                </div>
                <div class="col-12 col-sm-6">
                  <label class="form-label">Country <span class="text-danger">*</span></label>
                  <input type="text" name="country" value="{{ $nominee->country }}" class="form-control rounded-3" required />
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endforeach

@endsection
