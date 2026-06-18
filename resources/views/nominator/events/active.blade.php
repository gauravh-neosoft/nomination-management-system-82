@extends('layouts.app')

@section('content')
<h2 class="mb-0">Active Events</h2>

<div class="d-flex justify-content-between align-items-center">
  <p class="text-secondary">
    Events currently open for nomination submission
  </p>

  <div>
    <span class="badge fw-normal rounded-pill bg-light-green text-success px-3 py-2 me-3 fs-09">
      {{ $openCount }} Open
    </span>
    <span class="badge fw-normal rounded-pill bg-light-yellow text-warning px-3 py-2 fs-09">
      {{ $closingSoonCount }} Closing soon
    </span>
  </div>
</div>

<!-- Filters -->
<div class="border rounded-3 p-2 my-3 bg-white">
  <div class="d-flex align-items-center gap-3">
    <!-- Search -->
    <div class="flex-grow-1">
      <div class="d-flex align-items-center">
        <i class="bi bi-search text-secondary me-2"></i>
        <input
          type="text"
          id="eventSearch"
          class="form-control border-0 shadow-none p-0"
          placeholder="Search by event name.."
          onkeyup="filterActiveEvents()"
        />
      </div>
    </div>

    <div class="d-flex align-items-center gap-2">
      <label for="dateSort" class="fw-normal text-normal text-secondary">
        Date:
      </label>
      <select
        class="form-select form-select-sm shadow-none text-normal"
        id="dateSort"
        onchange="sortActiveEvents()"
      >
        <option value="newest" selected>Newest</option>
        <option value="oldest">Oldest</option>
      </select>
    </div>
  </div>
</div>
<!-- Filters End -->

<!-- Table -->
<div class="border rounded-3 p-2">
  <div class="table-responsive">
    <table class="table table-borderless align-middle" id="activeEventsTable">
      <thead>
        <tr class="table-light">
          <th scope="col" style="min-width: 70px">Serial No.</th>
          <th scope="col" style="min-width: 200px">Event Name</th>
          <th scope="col" style="min-width: 120px">Start Date</th>
          <th scope="col" style="min-width: 120px">End Date</th>
          <th scope="col" style="min-width: 200px">Nominees</th>
          <th scope="col" style="min-width: 100px">Status</th>
          <th scope="col" style="min-width: 100px">Nominations</th>
        </tr>
      </thead>
      <tbody>
        @forelse($events as $event)
        <tr class="{{ $loop->iteration % 2 == 0 ? 'table-light' : '' }}" data-event-name="{{ strtolower($event->name) }}" data-created-at="{{ $event->created_at }}">
          <td>{{ $loop->iteration }}</td>
          <td class="fw-semibold">{{ $event->name }}</td>
          <td>{{ $event->formatted_start_date }}</td>
          <td>{{ $event->formatted_end_date }}</td>
          <td>
            <div class="d-flex justify-content-center gap-3 align-items-center">
              <div class="d-flex align-items-center">
                <i class="bi bi-people text-primary me-2"></i><span>{{ $event->nominees_count }}</span>
              </div>
              <div class="vr text-light-grey"></div>
              <button
                class="btn btn-sm icon-bg-light-blue text-primary border-0"
                data-bs-toggle="modal"
                data-bs-target="#nominationListModal-{{ $event->id }}"
              >
                View List
              </button>
            </div>
          </td>
          <td>
            @if($event->is_closing_soon)
              <div class="w-fit mx-auto badge rounded-pill bg-light-yellow text-warning px-2 py-1 fw-normal d-flex align-items-center gap-2">
                <span class="rounded-circle bg-warning d-inline-block" style="width: 7px; height: 7px"></span>
                Closing soon
              </div>
            @else
              <div class="w-fit mx-auto badge rounded-pill bg-light-green text-success px-2 py-1 fw-normal d-flex align-items-center gap-2">
                <span class="rounded-circle bg-success d-inline-block" style="width: 7px; height: 7px"></span>
                Open
              </div>
            @endif
          </td>
          <td>
            <a href="{{ route('nominator-nominate-form', $event->id) }}" class="btn btn-primary btn-sm bg-primary border-0 text-white text-decoration-none">
              Submit
            </a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center text-muted py-4">No active events found.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<!-- Table ends -->

<!-- Modals Container -->
@foreach($events as $event)
<!-- Nomination list modal for {{ $event->name }} -->
<div class="modal fade" id="nominationListModal-{{ $event->id }}">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header justify-content-between">
        <h3 class="modal-title fs-5" id="nominationListLabel-{{ $event->id }}">
          Nominations list - {{ $event->name }}
        </h3>
        <div class="d-flex align-items-center gap-3">
          <a
            href="{{ route('nominator-download-template', $event->id) }}"
            class="btn btn-secondary bg-secondary text-black border-grey btn-sm rounded-3 border-0 text-decoration-none d-inline-flex align-items-center"
          >
            <i class="bi bi-download me-2"></i> Download Sample File
          </a>
          <button
            type="button"
            class="btn btn-primary btn-sm rounded-3"
            data-bs-toggle="modal"
            data-bs-target="#bulkUploadModal"
            data-event-id="{{ $event->id }}"
            data-event-name="{{ $event->name }}"
          >
            <i class="bi bi-upload me-2"></i>
            Bulk Upload
          </button>

          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
          ></button>
        </div>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row mb-3">
            <div class="col-6 ps-0">
              <div class="px-4 py-3 modal-card-bg rounded-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                  <div class="icon-with-bg bg-white d-flex justify-content-center align-items-center">
                    <i class="bi bi-people text-primary"></i>
                  </div>
                  <h4 class="mb-0 fw-semibold fs-6">Total Nominees</h4>
                </div>
                <p class="stat-value mb-0">{{ sprintf('%02d', count($event->nominees)) }}</p>
              </div>
            </div>
            <div class="col-6 pe-0">
              <div class="px-4 py-3 modal-card-bg rounded-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                  <div class="icon-with-bg bg-light-yellow d-flex justify-content-center align-items-center">
                    <i class="bi bi-clock-history text-warning"></i>
                  </div>
                  <h4 class="mb-0 fw-semibold fs-6">Remaining Limit</h4>
                </div>
                <p class="stat-value mb-0">
                  {{ sprintf('%02d', max(0, $event->max_nominees_per_form - count($event->nominees))) }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Nominee Listing Table -->
        <div class="border rounded-3 p-2">
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
                  <th scope="col" style="min-width: 200px">Job Level</th>
                  <th scope="col" style="min-width: 200px">Primary Account Manager Name</th>
                  <th scope="col" style="min-width: 200px">Primary Account Manager Email ID</th>
                  <th scope="col" style="min-width: 200px">Account Manager Email ID</th>
                  <th scope="col" style="min-width: 200px">Account Manager Email ID 2</th>
                  <th scope="col" style="min-width: 150px">Business/IT</th>
                </tr>
              </thead>
              <tbody>
                @forelse($event->nominees as $nominee)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $nominee->invite_status }}</td>
                  <td>
                    @if($nominee->approval_status === 'approved')
                      <div class="w-fit mx-auto badge rounded-pill bg-light-green text-success px-2 py-1 fw-normal d-flex align-items-center gap-2">
                        <span class="rounded-circle bg-success d-inline-block" style="width: 7px; height: 7px"></span>
                        Approved
                      </div>
                    @elseif($nominee->approval_status === 'pending')
                      <div class="w-fit mx-auto badge rounded-pill bg-light-yellow text-warning px-2 py-1 fw-normal d-flex align-items-center gap-2">
                        <span class="rounded-circle bg-warning d-inline-block" style="width: 7px; height: 7px"></span>
                        Pending
                      </div>
                    @else
                      <div class="w-fit mx-auto badge rounded-pill bg-light-red text-danger px-2 py-1 fw-normal d-flex align-items-center gap-2">
                        <span class="rounded-circle bg-danger d-inline-block" style="width: 7px; height: 7px"></span>
                        Rejected
                      </div>
                    @endif
                  </td>
                  <td>
                    @if($nominee->approval_status === 'approved')
                      <button class="btn btn-link text-primary text-decoration-none p-0 border-0" onclick="alert('Add remark clicked')">
                        Add Remark
                      </button>
                    @else
                      <button class="btn btn-link text-primary text-decoration-none p-0 border-0" onclick="alert('Edit nominee clicked')">
                        Edit
                      </button>
                    @endif
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
                  <td>{{ $nominee->account_manager_email_2 ?? 'N/A' }}</td>
                  <td>{{ $nominee->business_or_it }}</td>
                  
                </tr>
                @empty
                <tr>
                  <td colspan="18" class="text-center text-muted py-4">No nominee entries submitted yet.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-primary btn-sm" onclick="alert('Showing full list export options...')">
          View All
        </button>
        <div>
          <button
            type="button"
            data-bs-dismiss="modal"
            class="btn btn-secondary bg-white text-black border-grey btn-sm me-2"
          >
            Close
          </button>
          <button type="button" class="btn btn-primary btn-sm" onclick="alert('Proceeding to submit nomination form...')">
            Nominate Now
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
@endforeach

@include('layouts.bulk-upload-modal')

<!-- Bulk Upload Success Modal -->
<div class="modal fade" id="uploadSuccessModal" tabindex="-1" aria-labelledby="uploadSuccessLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width: 400px">
    <div class="modal-content">
      <div class="modal-header border-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body d-flex flex-column align-items-center">
        <h4 class="modal-title fs-5 text-center mb-3 fst-italic text-primary" id="uploadSuccessLabel">
          newNominations.csv
        </h4>

        <div class="mb-3">
          <img
            src="{{ asset('assets/icons/modal-success.svg') }}"
            alt="Success logo"
            onerror="this.src='https://cdn-icons-png.flaticon.com/512/190/190411.png'; this.style.width='60px';"
          />
        </div>
        <h3 class="text-center h5 fw-bold">Uploaded Successfully</h3>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
function filterActiveEvents() {
    const query = document.getElementById('eventSearch').value.toLowerCase();
    const rows = document.querySelectorAll('#activeEventsTable tbody tr');
    
    rows.forEach(row => {
        const eventName = row.getAttribute('data-event-name');
        if (eventName) {
            if (eventName.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
}

function sortActiveEvents() {
    const sortBy = document.getElementById('dateSort').value;
    const tbody = document.querySelector('#activeEventsTable tbody');
    const rows = Array.from(tbody.querySelectorAll('tr[data-created-at]'));
    
    rows.sort((a, b) => {
        const dateA = new Date(a.getAttribute('data-created-at'));
        const dateB = new Date(b.getAttribute('data-created-at'));
        
        return sortBy === 'newest' ? dateB - dateA : dateA - dateB;
    });
    
    // Clear and append sorted rows
    rows.forEach(row => tbody.appendChild(row));
}
</script>
@endpush

@endsection
