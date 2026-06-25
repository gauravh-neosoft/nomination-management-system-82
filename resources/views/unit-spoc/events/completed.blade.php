@extends('layouts.app')

@section('title', 'Unit SPOC - Completed Events')

@section('content')
  <h2 class="mb-0">Completed Events</h2>
  <p class="text-secondary">Successfully Completed Events</p>

  @if($errors->any())
    <div class="alert alert-danger mt-3 rounded-4">
      <ul class="mb-0">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
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
            id="search-event-input"
            class="form-control border-0 shadow-none p-0"
            placeholder="Search by event name.."
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

  <!-- Table -->
  <div class="border rounded-3 p-2">
    <div class="d-flex justify-content-between align-items-center mb-2 px-2">
      <p class="mb-0 fw-light fs-09" id="rows-count-display">{{ count($completedEvents) }} Rows</p>
    </div>
    
    <div class="table-responsive">
      <table class="table table-borderless align-middle mb-0" id="completed-events-table">
        <thead>
          <tr class="table-light">
            <th scope="col" style="min-width: 70px" class="text-center fs-09 fw-semibold">Serial No.</th>
            <th scope="col" style="min-width: 120px" class="fs-09 fw-semibold">Event Code</th>
            <th scope="col" style="min-width: 250px" class="fs-09 fw-semibold">Event Name</th>
            <th scope="col" style="min-width: 150px" class="fs-09 fw-semibold">Event Date</th>
            <th scope="col" style="min-width: 200px" class="text-center fs-09 fw-semibold">Nominees</th>
          </tr>
        </thead>
        <tbody class="event-rows-body">
          @forelse($completedEvents as $event)
            <tr class="event-row {{ $loop->iteration % 2 == 0 ? 'table-light' : '' }}"
                data-name="{{ strtolower($event->name) }}"
                data-time="{{ Carbon\Carbon::parse($event->start_date)->timestamp }}">
              <td class="text-center row-index">{{ $loop->iteration }}</td>
              <td>{{ $event->event_code }}</td>
              <td>
                <p class="lh-1 mb-1 fw-bold text-dark fs-09">{{ $event->name }}</p>
                <small class="text-light-grey lh-1">{{ $event->type === 'hospitality' ? 'Hospitality' : 'Non-Hospitality' }} Event</small>
              </td>
              <td>{{ $event->formatted_start_date }}</td>
              <td class="text-center">
                <div class="d-flex justify-content-center align-items-center gap-3">
                  <div class="d-flex align-items-center">
                    <i class="bi bi-people text-primary me-2"></i>
                    <span>{{ $event->nominees_count }}</span>
                  </div>
                  <div class="vr text-light-grey" style="height: 18px;"></div>
                  <button
                    class="btn btn-sm icon-bg-light-blue text-primary border-0 rounded-3 px-3 fw-semibold"
                    data-bs-toggle="modal"
                    data-bs-target="#nominationListModal-{{ $event->id }}"
                  >
                    View List
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr class="no-records-row">
              <td colspan="5" class="text-center text-secondary py-4">No completed events found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modals for Completed Nominees Lists -->
  @foreach($completedEvents as $event)
    <div class="modal fade" id="nominationListModal-{{ $event->id }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header justify-content-between align-items-center">
            <div>
              <h3 class="modal-title fs-5 fw-bold">
                Nominations list - {{ $event->name }}
              </h3>
              <p class="text-secondary text-small mb-0">List of candidates who participated in this completed event.</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-0">
            <div class="table-responsive">
              <table class="table table-borderless align-middle mb-0">
                <thead>
                  <tr class="table-light">
                    <th scope="col" style="min-width: 80px" class="text-center fs-08 fw-semibold text-uppercase">Serial No.</th>
                    <th scope="col" style="min-width: 130px" class="fs-08 fw-semibold text-uppercase">Invite Status</th>
                    <th scope="col" style="min-width: 180px" class="fs-08 fw-semibold text-uppercase">GDPR Compliant</th>
                    <th scope="col" style="min-width: 120px" class="fs-08 fw-semibold text-uppercase">Unit</th>
                    <th scope="col" style="min-width: 120px" class="fs-08 fw-semibold text-uppercase">Sub Unit</th>
                    <th scope="col" style="min-width: 160px" class="fs-08 fw-semibold text-uppercase">Name</th>
                    <th scope="col" style="min-width: 200px" class="fs-08 fw-semibold text-uppercase">Email</th>
                    <th scope="col" style="min-width: 140px" class="fs-08 fw-semibold text-uppercase">Company</th>
                    <th scope="col" style="min-width: 140px" class="fs-08 fw-semibold text-uppercase">Job Title</th>
                    <th scope="col" style="min-width: 100px" class="fs-08 fw-semibold text-uppercase">Job Level</th>
                    <th scope="col" style="min-width: 180px" class="fs-08 fw-semibold text-uppercase">Primary AM Name</th>
                    <th scope="col" style="min-width: 180px" class="fs-08 fw-semibold text-uppercase">Primary AM Email</th>
                    <th scope="col" style="min-width: 180px" class="fs-08 fw-semibold text-uppercase">AM Email 1</th>
                    <th scope="col" style="min-width: 180px" class="fs-08 fw-semibold text-uppercase">AM Email 2</th>
                    <th scope="col" style="min-width: 120px" class="fs-08 fw-semibold text-uppercase">Business/IT</th>
                    <th scope="col" style="min-width: 120px" class="fs-08 fw-semibold text-uppercase">Country</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($event->nominees ?? [] as $nominee)
                    <tr class="{{ $loop->iteration % 2 == 0 ? 'table-light' : '' }}">
                      <td class="text-center">{{ $loop->iteration }}</td>
                      <td>
                        @if($nominee->invite_status === 'Accepted')
                          <span class="badge rounded-pill bg-light-green text-success px-2 py-1 fw-normal">Accepted</span>
                        @elseif($nominee->invite_status === 'Declined')
                          <span class="badge rounded-pill bg-light-danger text-danger px-2 py-1 fw-normal">Declined</span>
                        @else
                          <span class="badge rounded-pill bg-light-secondary text-secondary px-2 py-1 fw-normal">{{ $nominee->invite_status }}</span>
                        @endif
                      </td>
                      <td>{{ $nominee->gdpr_compliance }}</td>
                      <td>{{ $nominee->unit }}</td>
                      <td>{{ $nominee->sub_unit }}</td>
                      <td class="fw-semibold text-dark">{{ $nominee->full_name }}</td>
                      <td>{{ $nominee->email }}</td>
                      <td>{{ $nominee->company }}</td>
                      <td>{{ $nominee->title }}</td>
                      <td>Level {{ $nominee->job_level }}</td>
                      <td>{{ $nominee->primary_account_manager_name }}</td>
                      <td>{{ $nominee->primary_account_manager_email }}</td>
                      <td>{{ $nominee->account_manager_email_1 ?? 'N/A' }}</td>
                      <td>{{ $nominee->account_manager_email_2 ?? 'N/A' }}</td>
                      <td>{{ $nominee->business_or_it }}</td>
                      <td>{{ $nominee->country }}</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="16" class="text-center text-secondary py-4">No nominee records found for this event.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  @endforeach
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const searchInput = document.getElementById('search-event-input');
      const sortSelect = document.getElementById('sort-order-select');
      const countDisplay = document.getElementById('rows-count-display');

      function filterAndSort() {
        const query = searchInput.value.toLowerCase().trim();
        const sortOrder = sortSelect.value;
        const rowsBody = document.querySelector('.event-rows-body');
        const rows = Array.from(rowsBody.querySelectorAll('.event-row'));
        const noRecordsRow = rowsBody.querySelector('.no-records-row');

        let visibleCount = 0;

        rows.forEach(row => {
          const name = row.getAttribute('data-name') || '';
          if (name.includes(query)) {
            row.style.display = '';
            visibleCount++;
          } else {
            row.style.display = 'none';
          }
        });

        if (noRecordsRow) {
          noRecordsRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
        }

        const sortedRows = rows.sort((a, b) => {
          const timeA = parseInt(a.getAttribute('data-time') || 0);
          const timeB = parseInt(b.getAttribute('data-time') || 0);
          return sortOrder === 'newest' ? (timeB - timeA) : (timeA - timeB);
        });

        let index = 1;
        sortedRows.forEach(row => {
          rowsBody.appendChild(row);
          if (row.style.display !== 'none') {
            row.className = `event-row ${index % 2 === 0 ? 'table-light' : ''}`;
            const idxCell = row.querySelector('.row-index');
            if (idxCell) {
              idxCell.textContent = index;
            }
            index++;
          }
        });

        countDisplay.textContent = visibleCount + ' Rows';
      }

      searchInput.addEventListener('input', filterAndSort);
      sortSelect.addEventListener('change', filterAndSort);
    });
  </script>
@endpush
