@extends('layouts.app')

@section('title', 'Unit SPOC - Active Events')

@section('content')
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <h2 class="mb-0">Active Events</h2>
      <p class="text-secondary">
        Events currently open for nomination submission
      </p>
    </div>

    <div class="d-flex align-items-center gap-2">
      <p class="badge fw-normal rounded-pill bg-light-green text-success px-3 py-2 mb-0">
        {{ $openCount }} Open
      </p>
      <p class="badge fw-normal rounded-pill closing-soon px-3 py-2 mb-0">
        {{ $closingSoonCount }} Closing soon
      </p>
    </div>
  </div>

  @if($errors->any())
    <div class="alert alert-danger mt-3 rounded-4">
      <ul class="mb-0">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @if(session('success'))
    <div class="alert alert-success mt-3 rounded-4">
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

  <div class="border rounded-3 p-2">
    <div class="underline-tabs">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
        <ul class="nav nav-tabs border-0" id="underlineTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button
              class="nav-link active text-black fw-semibold"
              id="current-events-tab"
              data-bs-toggle="tab"
              data-bs-target="#current_events"
              type="button"
              role="tab"
            >
              Current Events
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button
              class="nav-link text-black fw-semibold"
              id="pending-events-tab"
              data-bs-toggle="tab"
              data-bs-target="#nominations_pending"
              type="button"
              role="tab"
            >
              Nominations Pending
            </button>
          </li>
        </ul>

        <div class="d-flex align-items-center gap-3">
          <p class="mb-0 fw-light fs-09" id="rows-count-display">{{ count($currentEvents) }} Rows</p>
        </div>
      </div>

      <div class="tab-content" id="underlineTabsContent">
        <!-- Current Events Tab -->
        <div
          class="tab-pane fade show active"
          id="current_events"
          role="tabpanel"
          aria-labelledby="current-events-tab"
        >
          <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0" id="current-events-table">
              <thead>
                <tr class="table-light">
                  <th scope="col" style="min-width: 100px" class="text-center fs-09 fw-semibold">Serial No.</th>
                  <th scope="col" style="min-width: 120px" class="fs-09 fw-semibold">Event Code</th>
                  <th scope="col" style="min-width: 100px" class="text-center fs-09 fw-semibold">Status</th>
                  <th scope="col" style="min-width: 250px" class="fs-09 fw-semibold">Event Name</th>
                  <th scope="col" style="min-width: 200px" class="text-center fs-09 fw-semibold">Timeline</th>
                  <th scope="col" style="min-width: 150px" class="text-center fs-09 fw-semibold">Location</th>
                  <th scope="col" style="min-width: 150px" class="text-center fs-09 fw-semibold">Nominations</th>
                </tr>
              </thead>
              <tbody class="event-rows-body">
                @forelse($currentEvents as $event)
                  <tr class="event-row {{ $loop->iteration % 2 == 0 ? 'table-light' : '' }}" 
                      data-name="{{ strtolower($event->name) }}" 
                      data-time="{{ Carbon\Carbon::parse($event->start_date)->timestamp }}">
                    <td class="text-center row-index">{{ $loop->iteration }}</td>
                    <td>{{ $event->event_code }}</td>
                    <td>
                      @if($event->is_closing_soon)
                        <div class="w-fit mx-auto badge rounded-pill closing-soon px-2 py-1 fw-normal d-flex align-items-center gap-2">
                          <span class="rounded-circle bg-yellow d-inline-block" style="width: 7px; height: 7px"></span>
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
                      <p class="lh-1 mb-1 fw-bold text-dark fs-09">{{ $event->name }}</p>
                      <small class="text-light-grey lh-1">{{ $event->type === 'hospitality' ? 'Hospitality' : 'Non-Hospitality' }} Event</small>
                    </td>
                    <td class="text-center">{{ $event->formatted_start_date }} - {{ $event->formatted_end_date }}</td>
                    <td class="text-center">{{ $event->location ?? 'N/A' }}</td>
                    <td class="text-center">
                      <a href="{{ route('nominator-nominate-form', $event->id) }}" class="btn btn-primary btn-sm bg-primary border-0 rounded-3 px-3">
                        Submit
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr class="no-records-row">
                    <td colspan="7" class="text-center text-secondary py-4">No active events found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <!-- Nominations Pending Tab -->
        <div
          class="tab-pane fade"
          id="nominations_pending"
          role="tabpanel"
          aria-labelledby="pending-events-tab"
        >
          <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0" id="pending-events-table">
              <thead>
                <tr class="table-light">
                  <th scope="col" style="min-width: 100px" class="text-center fs-09 fw-semibold">Serial No.</th>
                  <th scope="col" style="min-width: 120px" class="fs-09 fw-semibold">Event Code</th>
                  <th scope="col" style="min-width: 100px" class="text-center fs-09 fw-semibold">Status</th>
                  <th scope="col" style="min-width: 250px" class="fs-09 fw-semibold">Event Name</th>
                  <th scope="col" style="min-width: 200px" class="text-center fs-09 fw-semibold">Timeline</th>
                  <th scope="col" style="min-width: 150px" class="text-center fs-09 fw-semibold">Location</th>
                  <th scope="col" style="min-width: 150px" class="text-center fs-09 fw-semibold">Nominator</th>
                  <th scope="col" style="min-width: 250px" class="text-center fs-09 fw-semibold">Nominations</th>
                </tr>
              </thead>
              <tbody class="event-rows-body">
                @forelse($pendingNominationsEvents as $event)
                  <tr class="event-row {{ $loop->iteration % 2 == 0 ? 'table-light' : '' }}" 
                      data-name="{{ strtolower($event->event_name) }}" 
                      data-time="{{ Carbon\Carbon::parse($event->start_date)->timestamp }}">
                    <td class="text-center row-index">{{ $loop->iteration }}</td>
                    <td>{{ $event->event_code }}</td>
                    <td>
                      @if($event->is_closing_soon)
                        <div class="w-fit mx-auto badge rounded-pill closing-soon px-2 py-1 fw-normal d-flex align-items-center gap-2">
                          <span class="rounded-circle bg-yellow d-inline-block" style="width: 7px; height: 7px"></span>
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
                      <p class="lh-1 mb-1 fw-bold text-dark fs-09">{{ $event->event_name }}</p>
                      <small class="text-light-grey lh-1">{{ $event->event_type === 'hospitality' ? 'Hospitality' : 'Non-Hospitality' }} Event</small>
                    </td>
                    <td class="text-center">{{ $event->formatted_start_date }} - {{ $event->formatted_end_date }}</td>
                    <td class="text-center">Bangalore, India</td>
                    <td class="text-center">{{ $event->nominator_name }} {{ substr($event->nominator_last_name, 0, 1) }}.</td>
                    <td class="text-center">
                      <div class="d-flex justify-content-center align-items-center gap-3">
                        <div class="d-flex align-items-center">
                          <i class="bi bi-people text-primary me-2"></i>
                          <span class="fw-semibold">{{ $event->pending_count }}</span>
                        </div>
                        <div class="vr text-light-grey" style="height: 18px;"></div>
                        <a href="{{ route('unit-spoc-nominations') }}?event_id={{ $event->event_id }}" class="btn btn-sm icon-bg-light-blue text-primary border-0 rounded-3 px-3 fw-semibold">
                          View List
                        </a>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr class="no-records-row">
                    <td colspan="8" class="text-center text-secondary py-4">No pending nominations found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const searchInput = document.getElementById('search-event-input');
      const sortSelect = document.getElementById('sort-order-select');
      const countDisplay = document.getElementById('rows-count-display');

      // Keep track of active tab
      let activeTabId = 'current_events';

      // Update active tab rows count
      function updateRowsCount() {
        const activeTabPane = document.getElementById(activeTabId);
        const rows = activeTabPane.querySelectorAll('.event-row:not([style*="display: none"])');
        countDisplay.textContent = rows.length + ' Rows';
      }

      // Handle search and sorting client side
      function filterAndSort() {
        const query = searchInput.value.toLowerCase().trim();
        const sortOrder = sortSelect.value;
        const activeTabPane = document.getElementById(activeTabId);
        const rowsBody = activeTabPane.querySelector('.event-rows-body');
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

        // Toggle "no records" row
        if (noRecordsRow) {
          noRecordsRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
        }

        // Sort rows
        const sortedRows = rows.sort((a, b) => {
          const timeA = parseInt(a.getAttribute('data-time') || 0);
          const timeB = parseInt(b.getAttribute('data-time') || 0);
          return sortOrder === 'newest' ? (timeB - timeA) : (timeA - timeB);
        });

        // Re-append in sorted order and fix zebra striping and serial numbers
        let index = 1;
        sortedRows.forEach(row => {
          rowsBody.appendChild(row);
          if (row.style.display !== 'none') {
            // Update row background class
            row.className = `event-row ${index % 2 === 0 ? 'table-light' : ''}`;
            const idxCell = row.querySelector('.row-index');
            if (idxCell) {
              idxCell.textContent = index;
            }
            index++;
          }
        });

        updateRowsCount();
      }

      // Listen to tab switch events
      document.getElementById('current-events-tab').addEventListener('shown.bs.tab', function () {
        activeTabId = 'current_events';
        filterAndSort();
      });

      document.getElementById('pending-events-tab').addEventListener('shown.bs.tab', function () {
        activeTabId = 'nominations_pending';
        filterAndSort();
      });

      // Listen to input fields
      searchInput.addEventListener('input', filterAndSort);
      sortSelect.addEventListener('change', filterAndSort);

      // Initial run
      filterAndSort();
    });
  </script>
@endpush
