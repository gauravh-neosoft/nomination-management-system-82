@extends('layouts.app')

@section('title', 'Unit SPOC - Nominations')

@section('content')
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
      <h2 class="mb-0">Nominations</h2>
      <p class="text-secondary">View and manage unit nominations</p>
    </div>

    <div class="d-flex flex-wrap gap-3">
      <button
        id="btn-download-nominations"
        class="btn btn-primary btn-sm rounded-3 px-3"
      >
        <i class="bi bi-download me-2"></i> Download Nominations
      </button>
    </div>
  </div>

  @if($errors->any())
    <div class="alert alert-danger mt-2 rounded-4">
      <ul class="mb-0">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @if(session('success'))
    <div class="alert alert-success mt-2 rounded-4">
      {{ session('success') }}
    </div>
  @endif

  <!-- Filters -->
  <div class="border rounded-3 p-2 my-3 bg-white">
    <div class="d-flex align-items-center gap-3 flex-wrap">
      <!-- Search -->
      <div class="flex-grow-1">
        <div class="d-flex align-items-center">
          <i class="bi bi-search text-secondary me-2"></i>
          <input
            type="text"
            id="search-nominee-input"
            class="form-control border-0 shadow-none p-0"
            placeholder="Search by nominee, event or company.."
          />
        </div>
      </div>

      <div class="d-flex align-items-center gap-2">
        <label for="sort-order-select" class="fw-normal text-normal text-secondary mb-0">
          Date:
        </label>
        <select
          class="form-select form-select-sm shadow-none text-normal"
          id="sort-order-select"
        >
          <option value="newest" selected>Newest</option>
          <option value="oldest">Oldest</option>
        </select>
      </div>
    </div>
  </div>
  <!-- Filters End -->

  <div class="border rounded-3 p-2">
    <div class="underline-tabs">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
        <ul class="nav nav-tabs border-0" id="underlineTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button
              class="nav-link active text-black fw-semibold"
              id="by-nominator-tab"
              data-bs-toggle="tab"
              data-bs-target="#by_nominator"
              type="button"
              role="tab"
            >
              By Nominator
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button
              class="nav-link text-black fw-semibold"
              id="by-self-tab"
              data-bs-toggle="tab"
              data-bs-target="#by_self"
              type="button"
              role="tab"
            >
              By Self
            </button>
          </li>
        </ul>

        <!-- Quick Filters -->
        <div class="d-flex gap-2 align-items-center flex-wrap">
          <p class="text-secondary mb-0 text-small">Status Filter:</p>
          <button
            type="button"
            id="filter-approved"
            class="btn btn-sm btn-outline-success px-3 rounded-pill text-normal"
            data-status="approved"
          >
            Approved
          </button>
          <button
            type="button"
            id="filter-pending"
            class="btn btn-sm btn-outline-warning px-3 rounded-pill text-normal"
            data-status="pending"
          >
            Pending
          </button>
          <button
            type="button"
            id="filter-rejected"
            class="btn btn-sm btn-outline-danger px-3 rounded-pill text-normal"
            data-status="rejected"
          >
            Rejected
          </button>
          <span class="fw-light fs-09 ms-2" id="rows-count-display">0 Rows</span>
        </div>
      </div>

      <div class="tab-content" id="underlineTabsContent">
        <!-- By Nominator Tab -->
        <div
          class="tab-pane fade show active"
          id="by_nominator"
          role="tabpanel"
          aria-labelledby="by-nominator-tab"
        >
          <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0" id="by-nominator-table">
              <thead>
                <tr class="table-light">
                  <th scope="col" style="min-width: 60px" class="text-center fs-08 fw-semibold text-uppercase">Serial No.</th>
                  <th scope="col" style="min-width: 140px" class="text-center fs-08 fw-semibold text-uppercase">Invite Status</th>
                  <th scope="col" style="min-width: 130px" class="text-center fs-08 fw-semibold text-uppercase">Action</th>
                  <th scope="col" style="min-width: 150px" class="fs-08 fw-semibold text-uppercase">Nominator</th>
                  <th scope="col" style="min-width: 180px" class="fs-08 fw-semibold text-uppercase">GDPR Compliant</th>
                  <th scope="col" style="min-width: 200px" class="fs-08 fw-semibold text-uppercase">Event Name</th>
                  <th scope="col" style="min-width: 100px" class="fs-08 fw-semibold text-uppercase">Unit</th>
                  <th scope="col" style="min-width: 100px" class="fs-08 fw-semibold text-uppercase">Sub Unit</th>
                  <th scope="col" style="min-width: 160px" class="fs-08 fw-semibold text-uppercase">Name</th>
                  <th scope="col" style="min-width: 200px" class="fs-08 fw-semibold text-uppercase">Email</th>
                  <th scope="col" style="min-width: 130px" class="text-center fs-08 fw-semibold text-uppercase">Company</th>
                  <th scope="col" style="min-width: 140px" class="fs-08 fw-semibold text-uppercase">Job Title</th>
                  <th scope="col" style="min-width: 180px" class="fs-08 fw-semibold text-uppercase">Primary AM Name</th>
                  <th scope="col" style="min-width: 180px" class="fs-08 fw-semibold text-uppercase">Primary AM Email</th>
                  <th scope="col" style="min-width: 180px" class="fs-08 fw-semibold text-uppercase">AM Email 1</th>
                  <th scope="col" style="min-width: 180px" class="fs-08 fw-semibold text-uppercase">AM Email 2</th>
                  <th scope="col" style="min-width: 120px" class="fs-08 fw-semibold text-uppercase">Business/IT</th>
                  <th scope="col" style="min-width: 140px" class="text-center fs-08 fw-semibold text-uppercase">Approval Status</th>
                </tr>
              </thead>
              <tbody class="nominee-rows-body">
                @forelse($byNominator as $nominee)
                  <tr class="nominee-row {{ $loop->iteration % 2 == 0 ? 'table-light' : '' }}"
                      data-id="{{ $nominee->id }}"
                      data-status="{{ $nominee->approval_status }}"
                      data-name="{{ strtolower($nominee->full_name) }}"
                      data-event="{{ strtolower($nominee->event_name) }}"
                      data-company="{{ strtolower($nominee->company) }}"
                      data-time="{{ Carbon\Carbon::parse($nominee->created_at)->timestamp }}"
                      data-json="{{ json_encode($nominee) }}">
                    <td class="text-center row-index">{{ $loop->iteration }}</td>
                    <td class="text-center">
                      @if($nominee->invite_status === 'Accepted')
                        <span class="badge rounded-pill bg-light-green text-success px-2 py-1 fw-normal">Accepted</span>
                      @elseif($nominee->invite_status === 'Declined')
                        <span class="badge rounded-pill bg-light-danger text-danger px-2 py-1 fw-normal">Declined</span>
                      @else
                        <span class="badge rounded-pill bg-light-secondary text-secondary px-2 py-1 fw-normal">{{ $nominee->invite_status ?? 'Invite Pending' }}</span>
                      @endif
                    </td>
                    <td class="text-center">
                      @if($nominee->approval_status === 'pending')
                        <button
                          type="button"
                          class="btn btn-link text-primary text-decoration-none p-0 fw-semibold btn-edit-nominee"
                        >
                          Edit
                        </button>
                      @else
                        <button
                          type="button"
                          class="btn btn-link text-primary text-decoration-none p-0 fw-semibold btn-remark-nominee"
                        >
                          Remark
                        </button>
                      @endif
                    </td>
                    <td>{{ $nominee->nominator_name }} {{ substr($nominee->nominator_last_name, 0, 1) }}.</td>
                    <td>{{ $nominee->gdpr_compliance }}</td>
                    <td>{{ $nominee->event_name }}</td>
                    <td>{{ $nominee->unit }}</td>
                    <td>{{ $nominee->sub_unit }}</td>
                    <td class="fw-semibold text-dark">{{ $nominee->full_name }}</td>
                    <td>{{ $nominee->email }}</td>
                    <td class="text-center">{{ $nominee->company }}</td>
                    <td>{{ $nominee->title }}</td>
                    <td>{{ $nominee->primary_account_manager_name }}</td>
                    <td>{{ $nominee->primary_account_manager_email }}</td>
                    <td>{{ $nominee->account_manager_email_1 ?? 'N/A' }}</td>
                    <td>{{ $nominee->account_manager_email_2 ?? 'N/A' }}</td>
                    <td>{{ $nominee->business_or_it }}</td>
                    <td class="text-center approval-cell">
                      @if($nominee->approval_status === 'pending')
                        <div class="d-flex justify-content-center align-items-center gap-2">
                          <button class="btn btn-approve-action p-1" title="Approve">
                            <i class="bi bi-check-circle text-green fs-5"></i>
                          </button>
                          <div class="vr text-light-grey" style="height: 16px;"></div>
                          <button class="btn btn-reject-action p-1" title="Reject">
                            <i class="bi bi-x-circle text-danger fs-5"></i>
                          </button>
                        </div>
                      @elseif($nominee->approval_status === 'approved')
                        <div class="w-fit mx-auto badge rounded-pill bg-light-green text-success px-2 py-1 fw-normal d-flex align-items-center gap-1">
                          <span class="rounded-circle bg-success d-inline-block" style="width: 6px; height: 6px"></span>
                          Approved
                        </div>
                      @else
                        <div class="w-fit mx-auto badge rounded-pill bg-light-danger text-danger px-2 py-1 fw-normal d-flex align-items-center gap-1">
                          <span class="rounded-circle bg-danger d-inline-block" style="width: 6px; height: 6px"></span>
                          Rejected
                        </div>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr class="no-records-row">
                    <td colspan="18" class="text-center text-secondary py-4">No nominations found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <!-- By Self Tab -->
        <div
          class="tab-pane fade"
          id="by_self"
          role="tabpanel"
          aria-labelledby="by-self-tab"
        >
          <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0" id="by-self-table">
              <thead>
                <tr class="table-light">
                  <th scope="col" style="min-width: 60px" class="text-center fs-08 fw-semibold text-uppercase">Serial No.</th>
                  <th scope="col" style="min-width: 140px" class="text-center fs-08 fw-semibold text-uppercase">Invite Status</th>
                  <th scope="col" style="min-width: 130px" class="text-center fs-08 fw-semibold text-uppercase">Action</th>
                  <th scope="col" style="min-width: 180px" class="fs-08 fw-semibold text-uppercase">GDPR Compliant</th>
                  <th scope="col" style="min-width: 200px" class="fs-08 fw-semibold text-uppercase">Event Name</th>
                  <th scope="col" style="min-width: 100px" class="fs-08 fw-semibold text-uppercase">Unit</th>
                  <th scope="col" style="min-width: 100px" class="fs-08 fw-semibold text-uppercase">Sub Unit</th>
                  <th scope="col" style="min-width: 160px" class="fs-08 fw-semibold text-uppercase">Name</th>
                  <th scope="col" style="min-width: 200px" class="fs-08 fw-semibold text-uppercase">Email</th>
                  <th scope="col" style="min-width: 130px" class="text-center fs-08 fw-semibold text-uppercase">Company</th>
                  <th scope="col" style="min-width: 140px" class="fs-08 fw-semibold text-uppercase">Job Title</th>
                  <th scope="col" style="min-width: 180px" class="fs-08 fw-semibold text-uppercase">Primary AM Name</th>
                  <th scope="col" style="min-width: 180px" class="fs-08 fw-semibold text-uppercase">Primary AM Email</th>
                  <th scope="col" style="min-width: 180px" class="fs-08 fw-semibold text-uppercase">AM Email 1</th>
                  <th scope="col" style="min-width: 180px" class="fs-08 fw-semibold text-uppercase">AM Email 2</th>
                  <th scope="col" style="min-width: 120px" class="fs-08 fw-semibold text-uppercase">Business/IT</th>
                  <th scope="col" style="min-width: 140px" class="text-center fs-08 fw-semibold text-uppercase">Approval Status</th>
                </tr>
              </thead>
              <tbody class="nominee-rows-body">
                @forelse($bySelf as $nominee)
                  <tr class="nominee-row {{ $loop->iteration % 2 == 0 ? 'table-light' : '' }}"
                      data-id="{{ $nominee->id }}"
                      data-status="{{ $nominee->approval_status }}"
                      data-name="{{ strtolower($nominee->full_name) }}"
                      data-event="{{ strtolower($nominee->event_name) }}"
                      data-company="{{ strtolower($nominee->company) }}"
                      data-time="{{ Carbon\Carbon::parse($nominee->created_at)->timestamp }}"
                      data-json="{{ json_encode($nominee) }}">
                    <td class="text-center row-index">{{ $loop->iteration }}</td>
                    <td class="text-center">
                      @if($nominee->invite_status === 'Accepted')
                        <span class="badge rounded-pill bg-light-green text-success px-2 py-1 fw-normal">Accepted</span>
                      @elseif($nominee->invite_status === 'Declined')
                        <span class="badge rounded-pill bg-light-danger text-danger px-2 py-1 fw-normal">Declined</span>
                      @else
                        <span class="badge rounded-pill bg-light-secondary text-secondary px-2 py-1 fw-normal">{{ $nominee->invite_status ?? 'Invite Pending' }}</span>
                      @endif
                    </td>
                    <td class="text-center">
                      @if($nominee->approval_status === 'pending')
                        <button
                          type="button"
                          class="btn btn-link text-primary text-decoration-none p-0 fw-semibold btn-edit-nominee"
                        >
                          Edit
                        </button>
                      @else
                        <button
                          type="button"
                          class="btn btn-link text-primary text-decoration-none p-0 fw-semibold btn-remark-nominee"
                        >
                          Remark
                        </button>
                      @endif
                    </td>
                    <td>{{ $nominee->gdpr_compliance }}</td>
                    <td>{{ $nominee->event_name }}</td>
                    <td>{{ $nominee->unit }}</td>
                    <td>{{ $nominee->sub_unit }}</td>
                    <td class="fw-semibold text-dark">{{ $nominee->full_name }}</td>
                    <td>{{ $nominee->email }}</td>
                    <td class="text-center">{{ $nominee->company }}</td>
                    <td>{{ $nominee->title }}</td>
                    <td>{{ $nominee->primary_account_manager_name }}</td>
                    <td>{{ $nominee->primary_account_manager_email }}</td>
                    <td>{{ $nominee->account_manager_email_1 ?? 'N/A' }}</td>
                    <td>{{ $nominee->account_manager_email_2 ?? 'N/A' }}</td>
                    <td>{{ $nominee->business_or_it }}</td>
                    <td class="text-center approval-cell">
                      @if($nominee->approval_status === 'pending')
                        <div class="d-flex justify-content-center align-items-center gap-2">
                          <button class="btn btn-approve-action p-1" title="Approve">
                            <i class="bi bi-check-circle text-green fs-5"></i>
                          </button>
                          <div class="vr text-light-grey" style="height: 16px;"></div>
                          <button class="btn btn-reject-action p-1" title="Reject">
                            <i class="bi bi-x-circle text-danger fs-5"></i>
                          </button>
                        </div>
                      @elseif($nominee->approval_status === 'approved')
                        <div class="w-fit mx-auto badge rounded-pill bg-light-green text-success px-2 py-1 fw-normal d-flex align-items-center gap-1">
                          <span class="rounded-circle bg-success d-inline-block" style="width: 6px; height: 6px"></span>
                          Approved
                        </div>
                      @else
                        <div class="w-fit mx-auto badge rounded-pill bg-light-danger text-danger px-2 py-1 fw-normal d-flex align-items-center gap-1">
                          <span class="rounded-circle bg-danger d-inline-block" style="width: 6px; height: 6px"></span>
                          Rejected
                        </div>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr class="no-records-row">
                    <td colspan="17" class="text-center text-secondary py-4">No nominations found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Approve Nomination Modal -->
  <div
    class="modal fade"
    id="approveNominationModal"
    tabindex="-1"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form id="approve-nomination-form" method="POST" action="">
          @csrf
          <div class="modal-header border-0 pb-0">
            <div class="d-flex align-items-center gap-2">
              <div class="icon-with-bg bg-light-green d-flex justify-content-center align-items-center">
                <i class="bi bi-check-circle text-green"></i>
              </div>
              <h3 class="modal-title fs-5" id="approveNominationModalLabel">
                Approve Nomination
              </h3>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body d-flex flex-column gap-4">
            <p class="text-secondary">
              Are you sure you want to approve this nomination? This action will notify the nominator and move the application to the next stage.
            </p>

            <div class="bg-light-grey p-3 rounded-4">
              <div class="d-flex justify-content-between mb-1">
                <p class="text-uppercase text-small text-secondary mb-0">candidate</p>
                <p class="text-small fw-semibold mb-0" id="approve-candidate-name"></p>
              </div>
              <div class="d-flex justify-content-between">
                <p class="text-uppercase text-small text-secondary mb-0">event</p>
                <p class="text-small mb-0" id="approve-event-name"></p>
              </div>
            </div>
          </div>
          <div class="modal-footer border-0 flex-nowrap pt-0">
            <button type="button" data-bs-dismiss="modal" class="btn border w-100">
              Close
            </button>
            <button type="submit" class="btn btn-primary w-100">
              Approve
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Reject Nomination Modal -->
  <div
    class="modal fade"
    id="rejectNominationModal"
    tabindex="-1"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form id="reject-nomination-form" method="POST" action="">
          @csrf
          <div class="modal-header border-0 pb-0">
            <div class="d-flex align-items-center gap-2">
              <div class="icon-with-bg bg-light-orange d-flex justify-content-center align-items-center">
                <i class="bi bi-x-circle text-danger"></i>
              </div>
              <h3 class="modal-title fs-5" id="rejectNominationModalLabel">
                Reject Nomination
              </h3>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body d-flex flex-column gap-4">
            <p class="text-secondary">
              Are you sure you want to reject this nomination? Please provide a reason for the rejection below.
            </p>

            <div>
              <label for="reject-remark" class="form-label text-small fw-semibold text-secondary">
                Rejection Reason (Optional)
              </label>
              <textarea
                class="border-grey w-100 rounded-3 p-3 form-control"
                id="reject-remark"
                name="remark"
                rows="3"
                placeholder="Enter remarks.."
              ></textarea>
            </div>
          </div>
          <div class="modal-footer border-0 flex-nowrap pt-0">
            <button type="button" data-bs-dismiss="modal" class="btn border w-100">
              Close
            </button>
            <button type="submit" class="btn btn-primary w-100">
              Reject
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Add/Edit Remark Modal -->
  <div
    class="modal fade"
    id="addRemarkModal"
    tabindex="-1"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form id="remark-nomination-form" method="POST" action="">
          @csrf
          <div class="modal-header border-0">
            <h3 class="modal-title fs-5" id="addRemarkModalLabel">
              Add or Edit Remarks
            </h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <label for="spoc-remark-textarea" class="form-label text-small text-secondary fw-semibold">SPOC Remarks</label>
            <textarea
              class="border-grey w-100 rounded-3 p-3 form-control"
              id="spoc-remark-textarea"
              name="remark"
              rows="5"
              placeholder="Enter remarks.."
              required
            ></textarea>
          </div>
          <div class="modal-footer border-0 pt-0">
            <button type="submit" class="btn btn-primary w-100">
              Submit Remarks
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Nomination Modal -->
  <div class="modal fade" id="editNominationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <form id="edit-nomination-form" method="POST" action="">
          @csrf
          <div class="modal-header justify-content-between">
            <h3 class="modal-title fs-5" id="editNominationModalLabel">
              Edit Nomination
            </h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="border p-3 rounded-4 mb-3 bg-light-grey">
              <div class="container-fluid">
                <div class="row row-gap-3">
                  <!-- GDPR Compliance -->
                  <div class="col-12 col-md-6">
                    <label class="form-label">
                      GDPR Compliance
                      <span class="text-danger">*</span>
                    </label>
                    <select class="form-select shadow-none rounded-3" id="edit-gdpr" name="gdpr_compliance" required>
                      <option value="" disabled>Select</option>
                      @foreach($gdprOptions as $gdpr)
                        <option value="{{ $gdpr }}">{{ $gdpr }}</option>
                      @endforeach
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
                        <select class="form-select shadow-none rounded-3" id="edit-unit" name="unit" required>
                          <option value="" disabled>Select</option>
                          @foreach($units as $unit)
                            <option value="{{ $unit }}">{{ $unit }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-12 col-md-6">
                        <label class="form-label">
                          Sub Unit
                          <span class="text-danger">*</span>
                        </label>
                        <select class="form-select shadow-none rounded-3" id="edit-subunit" name="sub_unit" required>
                          <option value="" disabled>Select</option>
                          @foreach($subUnits as $subunit)
                            <option value="{{ $subunit }}">{{ $subunit }}</option>
                          @endforeach
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
                      class="form-control"
                      id="edit-firstname"
                      name="first_name"
                      placeholder="John"
                      required
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
                      class="form-control"
                      id="edit-lastname"
                      name="last_name"
                      placeholder="Doe"
                      required
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
                      class="form-control"
                      id="edit-email"
                      name="email"
                      placeholder="john.doe@example.com"
                      required
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
                      class="form-control"
                      id="edit-company"
                      name="company"
                      placeholder="Infosys"
                      required
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
                      class="form-control"
                      id="edit-title"
                      name="title"
                      placeholder="Architect"
                      required
                    />
                  </div>

                  <!-- Job Level -->
                  <div class="col-12 col-md-6">
                    <label class="form-label">
                      Job Level
                      <span class="text-danger">*</span>
                    </label>
                    <select class="form-select shadow-none rounded-3" id="edit-joblevel" name="job_level" required>
                      <option value="" disabled>Select</option>
                      <option value="1">Level 1</option>
                      <option value="2">Level 2</option>
                      <option value="3">Level 3</option>
                      <option value="4">Level 4</option>
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
                      class="form-control"
                      id="edit-pam-name"
                      name="primary_account_manager_name"
                      placeholder="Manager Name"
                      required
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
                      class="form-control"
                      id="edit-pam-email"
                      name="primary_account_manager_email"
                      placeholder="manager@example.com"
                      required
                    />
                  </div>

                  <!-- Account manager email id -->
                  <div class="col-12 col-sm-6">
                    <label class="form-label">
                      Account Manager Email ID 1
                    </label>
                    <input
                      type="email"
                      class="form-control"
                      id="edit-am-email-1"
                      name="account_manager_email_1"
                      placeholder="am1@example.com"
                    />
                  </div>

                  <!-- Account manager email id 2 -->
                  <div class="col-12 col-sm-6">
                    <label class="form-label">
                      Account Manager Email ID 2
                    </label>
                    <input
                      type="email"
                      class="form-control"
                      id="edit-am-email-2"
                      name="account_manager_email_2"
                      placeholder="am2@example.com"
                    />
                  </div>

                  <!-- Business / IT -->
                  <div class="col-12 col-sm-6">
                    <label class="form-label">
                      Business / IT
                      <span class="text-danger">*</span>
                    </label>
                    <select class="form-select shadow-none rounded-3" id="edit-businessit" name="business_or_it" required>
                      <option value="" disabled>Select</option>
                      <option value="Business">Business</option>
                      <option value="IT">IT</option>
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
                      class="form-control"
                      id="edit-country"
                      name="country"
                      placeholder="India"
                      required
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary bg-white text-black border-grey btn-sm me-2"
              data-bs-dismiss="modal"
            >
              Close
            </button>
            <button type="submit" class="btn btn-primary btn-sm">
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const searchInput = document.getElementById('search-nominee-input');
      const sortSelect = document.getElementById('sort-order-select');
      const countDisplay = document.getElementById('rows-count-display');

      // Modal instances and form elements
      const approveModal = new bootstrap.Modal(document.getElementById('approveNominationModal'));
      const rejectModal = new bootstrap.Modal(document.getElementById('rejectNominationModal'));
      const remarkModal = new bootstrap.Modal(document.getElementById('addRemarkModal'));
      const editModal = new bootstrap.Modal(document.getElementById('editNominationModal'));

      const approveForm = document.getElementById('approve-nomination-form');
      const rejectForm = document.getElementById('reject-nomination-form');
      const remarkForm = document.getElementById('remark-nomination-form');
      const editForm = document.getElementById('edit-nomination-form');

      // URL parameters checking for event_id
      const urlParams = new URLSearchParams(window.location.search);
      const targetEventId = urlParams.get('event_id');

      // Keep track of active tab and filters
      let activeTabId = 'by_nominator';
      let selectedStatusFilter = null; // 'approved', 'pending', 'rejected'

      // Hook tab change
      document.getElementById('by-nominator-tab').addEventListener('shown.bs.tab', function () {
        activeTabId = 'by_nominator';
        filterAndSort();
      });

      document.getElementById('by-self-tab').addEventListener('shown.bs.tab', function () {
        activeTabId = 'by_self';
        filterAndSort();
      });

      // Quick filter buttons click
      const filters = {
        'approved': document.getElementById('filter-approved'),
        'pending': document.getElementById('filter-pending'),
        'rejected': document.getElementById('filter-rejected')
      };

      Object.keys(filters).forEach(status => {
        filters[status].addEventListener('click', function () {
          if (selectedStatusFilter === status) {
            // Toggle off
            selectedStatusFilter = null;
            filters[status].classList.remove('active');
            filters[status].className = filters[status].className.replace('btn-success', 'btn-outline-success')
                                                                 .replace('btn-warning', 'btn-outline-warning')
                                                                 .replace('btn-danger', 'btn-outline-danger');
          } else {
            // Remove active from others
            Object.keys(filters).forEach(s => {
              filters[s].classList.remove('active');
              filters[s].className = filters[s].className.replace('btn-success', 'btn-outline-success')
                                                         .replace('btn-warning', 'btn-outline-warning')
                                                         .replace('btn-danger', 'btn-outline-danger');
            });
            // Toggle on this one
            selectedStatusFilter = status;
            filters[status].classList.add('active');
            if (status === 'approved') filters[status].className = filters[status].className.replace('btn-outline-success', 'btn-success');
            if (status === 'pending') filters[status].className = filters[status].className.replace('btn-outline-warning', 'btn-warning');
            if (status === 'rejected') filters[status].className = filters[status].className.replace('btn-outline-danger', 'btn-danger');
          }
          filterAndSort();
        });
      });

      function filterAndSort() {
        const query = searchInput.value.toLowerCase().trim();
        const sortOrder = sortSelect.value;
        const activeTabPane = document.getElementById(activeTabId);
        const rowsBody = activeTabPane.querySelector('.nominee-rows-body');
        const rows = Array.from(rowsBody.querySelectorAll('.nominee-row'));
        const noRecordsRow = rowsBody.querySelector('.no-records-row');

        let visibleCount = 0;

        rows.forEach(row => {
          const name = row.getAttribute('data-name') || '';
          const event = row.getAttribute('data-event') || '';
          const company = row.getAttribute('data-company') || '';
          const status = row.getAttribute('data-status') || '';
          const jsonStr = row.getAttribute('data-json') || '{}';
          const nomineeObj = JSON.parse(jsonStr);

          // Check if filtered by event_id query parameter
          let matchesEventParam = true;
          if (targetEventId && String(nomineeObj.event_id) !== targetEventId) {
            matchesEventParam = false;
          }

          const matchesQuery = name.includes(query) || event.includes(query) || company.includes(query);
          const matchesStatus = !selectedStatusFilter || status === selectedStatusFilter;

          if (matchesEventParam && matchesQuery && matchesStatus) {
            row.style.display = '';
            visibleCount++;
          } else {
            row.style.display = 'none';
          }
        });

        if (noRecordsRow) {
          noRecordsRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
        }

        // Sort
        const sortedRows = rows.sort((a, b) => {
          const timeA = parseInt(a.getAttribute('data-time') || 0);
          const timeB = parseInt(b.getAttribute('data-time') || 0);
          return sortOrder === 'newest' ? (timeB - timeA) : (timeA - timeB);
        });

        let index = 1;
        sortedRows.forEach(row => {
          rowsBody.appendChild(row);
          if (row.style.display !== 'none') {
            row.className = `nominee-row ${index % 2 === 0 ? 'table-light' : ''}`;
            const idxCell = row.querySelector('.row-index');
            if (idxCell) {
              idxCell.textContent = index;
            }
            index++;
          }
        });

        countDisplay.textContent = visibleCount + ' Rows';
      }

      // Input event listeners
      searchInput.addEventListener('input', filterAndSort);
      sortSelect.addEventListener('change', filterAndSort);

      // Prepopulate modals helper
      function getNomineeDataFromRowButton(btn) {
        const row = btn.closest('.nominee-row');
        const jsonStr = row.getAttribute('data-json');
        return JSON.parse(jsonStr);
      }

      // Action: Click Edit button
      document.querySelectorAll('.btn-edit-nominee').forEach(btn => {
        btn.addEventListener('click', function () {
          const nominee = getNomineeDataFromRowButton(this);
          editForm.action = `/unit-spoc/nominations/${nominee.id}/update`;
          document.getElementById('editNominationModalLabel').textContent = `Edit Nomination - ${nominee.full_name}`;
          document.getElementById('edit-gdpr').value = nominee.gdpr_compliance;
          document.getElementById('edit-unit').value = nominee.unit;
          document.getElementById('edit-subunit').value = nominee.sub_unit;
          document.getElementById('edit-firstname').value = nominee.first_name;
          document.getElementById('edit-lastname').value = nominee.last_name;
          document.getElementById('edit-email').value = nominee.email;
          document.getElementById('edit-company').value = nominee.company;
          document.getElementById('edit-title').value = nominee.title;
          document.getElementById('edit-joblevel').value = nominee.job_level;
          document.getElementById('edit-pam-name').value = nominee.primary_account_manager_name;
          document.getElementById('edit-pam-email').value = nominee.primary_account_manager_email;
          document.getElementById('edit-am-email-1').value = nominee.account_manager_email_1 || '';
          document.getElementById('edit-am-email-2').value = nominee.account_manager_email_2 || '';
          document.getElementById('edit-businessit').value = nominee.business_or_it;
          document.getElementById('edit-country').value = nominee.country;

          editModal.show();
        });
      });

      // Action: Click Remark button
      document.querySelectorAll('.btn-remark-nominee').forEach(btn => {
        btn.addEventListener('click', function () {
          const nominee = getNomineeDataFromRowButton(this);
          remarkForm.action = `/unit-spoc/nominations/${nominee.id}/remark`;
          document.getElementById('spoc-remark-textarea').value = nominee.spoc_comment || '';
          remarkModal.show();
        });
      });

      // Action: Click Approve checkmark icon
      document.querySelectorAll('.btn-approve-action').forEach(btn => {
        btn.addEventListener('click', function () {
          const nominee = getNomineeDataFromRowButton(this);
          approveForm.action = `/unit-spoc/nominations/${nominee.id}/approve`;
          document.getElementById('approve-candidate-name').textContent = nominee.full_name;
          document.getElementById('approve-event-name').textContent = nominee.event_name;
          approveModal.show();
        });
      });

      // Action: Click Reject cross icon
      document.querySelectorAll('.btn-reject-action').forEach(btn => {
        btn.addEventListener('click', function () {
          const nominee = getNomineeDataFromRowButton(this);
          rejectForm.action = `/unit-spoc/nominations/${nominee.id}/reject`;
          document.getElementById('reject-remark').value = nominee.spoc_comment || '';
          rejectModal.show();
        });
      });

      // Download nominations table data to CSV client side
      document.getElementById('btn-download-nominations').addEventListener('click', function () {
        const activeTabPane = document.getElementById(activeTabId);
        const rows = Array.from(activeTabPane.querySelectorAll('.nominee-row:not([style*="display: none"])'));
        
        if (rows.length === 0) {
          alert('No visible nominations to download.');
          return;
        }

        const headers = [
          'Serial No', 'Invite Status', 'Nominator', 'GDPR Compliant', 
          'Event Name', 'Unit', 'Sub Unit', 'Name', 'Email', 
          'Company', 'Job Title', 'Job Level', 'Primary AM Name', 
          'Primary AM Email', 'AM Email 1', 'AM Email 2', 'Business/IT', 'Approval Status'
        ];

        const csvRows = [headers.join(',')];

        rows.forEach((row, idx) => {
          const jsonStr = row.getAttribute('data-json');
          const nominee = JSON.parse(jsonStr);

          const values = [
            idx + 1,
            nominee.invite_status || 'Invite Pending',
            `${nominee.nominator_name} ${nominee.nominator_last_name}`,
            `"${nominee.gdpr_compliance.replace(/"/g, '""')}"`,
            `"${nominee.event_name.replace(/"/g, '""')}"`,
            nominee.unit,
            nominee.sub_unit,
            `"${nominee.full_name.replace(/"/g, '""')}"`,
            nominee.email,
            `"${nominee.company.replace(/"/g, '""')}"`,
            `"${nominee.title.replace(/"/g, '""')}"`,
            `"Level ${nominee.job_level}"`,
            `"${nominee.primary_account_manager_name.replace(/"/g, '""')}"`,
            nominee.primary_account_manager_email,
            nominee.account_manager_email_1 || 'N/A',
            nominee.account_manager_email_2 || 'N/A',
            nominee.business_or_it,
            nominee.approval_status
          ];

          csvRows.push(values.join(','));
        });

        const csvContent = "data:text/csv;charset=utf-8," + csvRows.join("\n");
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", `unit_spoc_nominations_${activeTabId}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      });

      // Initial filter execution
      filterAndSort();
    });
  </script>
@endpush
