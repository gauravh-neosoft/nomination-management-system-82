@extends('layouts.app')

@section('content')
<h2 class="mb-0">Nominations - {{ $event->name }}</h2>
<p class="text-secondary">View and manage nominations for this event.</p>

<!-- Filters -->
<div class="border rounded-3 p-2 my-3 bg-white">
  <form action="{{ route('event-ops-event-nominations', $event->id) }}" method="GET" class="d-flex align-items-center gap-3">
    <!-- Search -->
    <div class="flex-grow-1">
      <div class="d-flex align-items-center">
        <i class="bi bi-search text-secondary me-2"></i>
        <input
          type="text"
          name="search"
          value="{{ $search }}"
          class="form-control border-0 shadow-none p-0"
          placeholder="Search by nominee name or email.."
          onchange="this.form.submit()"
        />
      </div>
    </div>
    <div class="d-flex align-items-center gap-2">
      <input type="hidden" name="status" value="{{ $status ?? 'all' }}" />
      <button type="submit" class="btn btn-sm btn-primary">Search</button>
    </div>
  </form>
</div>
<!-- Filters End -->

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<!-- Table -->
<div class="border rounded-3 p-2">
  <!-- Quick Filters -->
  <div class="mb-3 d-flex gap-2 align-items-center flex-wrap">
    <span class="text-secondary">Status:</span>
    <a href="{{ route('event-ops-event-nominations', [$event->id, 'status' => 'all', 'search' => $search]) }}" class="btn btn-sm {{ ($status === 'all' || !$status) ? 'btn-primary' : 'bg-light text-dark' }} px-4">
      All
    </a>
    <a href="{{ route('event-ops-event-nominations', [$event->id, 'status' => 'approved', 'search' => $search]) }}" class="btn btn-sm {{ $status === 'approved' ? 'btn-success text-white' : 'bg-light-green text-success' }} px-4">
      Approved
    </a>
    <a href="{{ route('event-ops-event-nominations', [$event->id, 'status' => 'pending', 'search' => $search]) }}" class="btn btn-sm {{ $status === 'pending' ? 'btn-warning text-dark' : 'bg-light-warning text-warning' }} px-4">
      Pending
    </a>
  </div>

  <div class="table-responsive">
    <table class="table table-borderless align-middle">
      <thead>
        <tr class="table-light">
          <th scope="col" style="min-width: 100px">Serial No.</th>
          <th scope="col" style="min-width: 150px">Invite Status</th>
          <th scope="col" style="min-width: 150px">Approval Status</th>
          <th scope="col" style="min-width: 150px">Action</th>
          <th scope="col" style="min-width: 200px">GDPR Compliant</th>
          <th scope="col" style="min-width: 200px">Event Name</th>
          <th scope="col" style="min-width: 120px">Unit</th>
          <th scope="col" style="min-width: 120px">Sub Unit</th>
          <th scope="col" style="min-width: 200px">Name</th>
          <th scope="col" style="min-width: 200px">Email</th>
          <th scope="col" style="min-width: 150px">Company</th>
          <th scope="col" style="min-width: 120px">Job Title</th>
          <th scope="col" style="min-width: 120px">Job Level</th>
          <th scope="col" style="min-width: 200px">Primary Account Manager</th>
          <th scope="col" style="min-width: 200px">Primary AM Email</th>
          <th scope="col" style="min-width: 200px">AM Email 1</th>
          <th scope="col" style="min-width: 200px">AM Email 2</th>
          <th scope="col" style="min-width: 150px">Business/IT</th>
        </tr>
      </thead>
      <tbody>
        @forelse($nominations as $index => $nominee)
          <tr class="{{ $index % 2 == 1 ? 'table-light' : '' }}">
            <td>{{ $index + 1 }}</td>
            <td>
              <span class="badge bg-light-green text-success rounded-pill fw-normal px-2 py-1">
                {{ $nominee->invite_status ?: 'Invite Sent' }}
              </span>
            </td>
            <td>
              @if($nominee->approval_status === 'approved')
                <div class="badge rounded-pill bg-light-green text-success px-2 py-1 fw-normal d-inline-flex align-items-center gap-2">
                  <span class="rounded-circle bg-success d-inline-block" style="width: 7px; height: 7px"></span>
                  Approved
                </div>
              @elseif($nominee->approval_status === 'pending')
                <div class="badge rounded-pill bg-light-yellow text-warning px-2 py-1 fw-normal d-inline-flex align-items-center gap-2" style="background-color: #fff9e6; color: #d9a300;">
                  <span class="rounded-circle bg-warning d-inline-block" style="width: 7px; height: 7px"></span>
                  Pending
                </div>
              @else
                <div class="badge rounded-pill bg-light-danger text-danger px-2 py-1 fw-normal d-inline-flex align-items-center gap-2">
                  <span class="rounded-circle bg-danger d-inline-block" style="width: 7px; height: 7px"></span>
                  Rejected
                </div>
              @endif
            </td>
            <td>
              <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editNominationModal-{{ $nominee->id }}">Edit</button>
                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addRemarkModal-{{ $nominee->id }}">Remark</button>
              </div>
            </td>
            <td>{{ $nominee->gdpr_compliance }}</td>
            <td>{{ $event->name }}</td>
            <td>{{ $nominee->unit }}</td>
            <td>{{ $nominee->sub_unit }}</td>
            <td>{{ $nominee->full_name }}</td>
            <td>{{ $nominee->email }}</td>
            <td>{{ $nominee->company }}</td>
            <td>{{ $nominee->title }}</td>
            <td>{{ $nominee->job_level }}</td>
            <td>{{ $nominee->primary_account_manager_name }}</td>
            <td>{{ $nominee->primary_account_manager_email }}</td>
            <td>{{ $nominee->account_manager_email_1 }}</td>
            <td>{{ $nominee->account_manager_email_2 }}</td>
            <td>{{ $nominee->business_or_it }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="18" class="text-center text-muted py-4">No nominations found for this event.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<!-- Table ends -->

<!-- Modals -->
@foreach($nominations as $nominee)
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
