@extends('layouts.app')

@section('content')
<h2 class="mb-0">Reports Overview</h2>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
  <p class="text-secondary mb-0">View all the reports.</p>
</div>

<!-- Cards -->
<div class="row row-gap-3 mb-4">
  <!-- Card 1 -->
  <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="rounded-4 p-2 w-100 bg-light-blue" style="background-color: #e6f7ff;">
      <div class="bg-white p-3 rounded-4">
        <div class="d-flex justify-content-between align-items-end">
          <p class="stat-value text-primary mb-0">{{ $eventsCreated }}</p>
          <p class="fw-bold mb-0">Events Created</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Card 2 -->
  <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="rounded-4 p-2 w-100 bg-light-green">
      <div class="bg-white p-3 rounded-4">
        <div class="d-flex justify-content-between align-items-end">
          <p class="stat-value text-success mb-0">{{ $activeEvents }}</p>
          <p class="fw-bold mb-0">Active Events</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Card 3 -->
  <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="rounded-4 p-2 w-100 bg-light-purple">
      <div class="bg-white p-3 rounded-4">
        <div class="d-flex justify-content-between align-items-end">
          <p class="stat-value text-purple mb-0">{{ $completedEvents }}</p>
          <p class="fw-bold mb-0">Completed Events</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Card 4 -->
  <div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <div class="rounded-4 p-2 w-100 bg-light-warning">
      <div class="bg-white p-3 rounded-4">
        <div class="d-flex justify-content-between align-items-end">
          <p class="stat-value text-warning mb-0">{{ $totalNominations }}</p>
          <p class="fw-bold mb-0">Nominations</p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Cards ends -->

<!-- Filters -->
<div class="border rounded-3 p-2 my-4 bg-white">
  <form action="{{ route('event-ops-reports') }}" method="GET" class="d-flex align-items-center gap-3">
    <!-- Search -->
    <div class="flex-grow-1">
      <div class="d-flex align-items-center">
        <i class="bi bi-search text-secondary me-2"></i>
        <input
          type="text"
          name="search"
          value="{{ $search }}"
          class="form-control border-0 shadow-none p-0"
          placeholder="Search by event name.."
          onchange="this.form.submit()"
        />
      </div>
    </div>
    <button type="submit" class="btn btn-sm btn-primary">Search</button>
  </form>
</div>
<!-- Filters End -->

<!-- Table -->
<div class="border rounded-3 p-2">
  <div class="table-responsive">
    <table class="table table-borderless align-middle">
      <thead>
        <tr class="table-light">
          <th scope="col" style="min-width: 70px" class="text-center">Serial No.</th>
          <th scope="col" style="min-width: 200px">Event Name</th>
          <th scope="col" style="min-width: 150px" class="text-center">Timeline</th>
          <th scope="col" style="min-width: 120px" class="text-center">Nominees</th>
          <th scope="col" style="min-width: 120px" class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($events as $index => $event)
          <tr class="{{ $index % 2 == 1 ? 'table-light' : '' }}">
            <td class="text-center">{{ $index + 1 }}</td>
            <td>
              <span class="fw-semibold">{{ $event->name }}</span>
              <p class="text-small text-light-grey lh-1 mb-0">{{ ucfirst(str_replace('_', ' ', $event->type)) }} Event</p>
            </td>
            <td class="text-center">{{ $event->formatted_start_date }} - {{ $event->formatted_end_date }}</td>
            <td class="text-center">
              <div class="d-inline-flex align-items-center justify-content-center gap-3">
                <div class="d-flex align-items-center">
                  <i class="bi bi-people text-primary me-2"></i><span>{{ $event->nominees_count }}</span>
                </div>
                <div class="vr text-light-grey"></div>
                <button
                  class="btn btn-sm icon-bg-light-blue text-primary py-1"
                  data-bs-toggle="modal"
                  data-bs-target="#nominationListModal-{{ $event->id }}"
                >
                  View List
                </button>
              </div>
            </td>
            <td class="text-center">
              <button onclick="showToast('Exporting report for {{ $event->name }} as Excel...')" class="btn btn-secondary bg-white text-secondary border-grey btn-sm px-3 rounded-3">
                <i class="bi bi-download me-2"></i> Download
              </button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-4">No reports found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<!-- Table ends -->

<!-- Modals -->
@foreach($events as $event)
  <div class="modal fade" id="nominationListModal-{{ $event->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header justify-content-between">
          <h3 class="modal-title fs-5">
            Nominations list - {{ $event->name }}
          </h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-borderless align-middle">
              <thead>
                <tr class="table-light">
                  <th scope="col" class="text-center" style="min-width: 70px">Serial No.</th>
                  <th scope="col" style="min-width: 150px">Invite Status</th>
                  <th scope="col" style="min-width: 150px">GDPR Compliant</th>
                  <th scope="col" style="min-width: 200px">Event Name</th>
                  <th scope="col" style="min-width: 120px">Unit</th>
                  <th scope="col" style="min-width: 200px">Sub Unit</th>
                  <th scope="col" style="min-width: 150px">Name</th>
                  <th scope="col" style="min-width: 200px">Email</th>
                </tr>
              </thead>
              <tbody>
                @forelse($event->nominees as $index => $nominee)
                  <tr class="{{ $index % 2 == 1 ? 'table-light' : '' }}">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                      <span class="badge bg-light-green text-success rounded-pill fw-normal px-2 py-1">
                        {{ $nominee->invite_status ?: 'Invite Sent' }}
                      </span>
                    </td>
                    <td>{{ $nominee->gdpr_compliance }}</td>
                    <td>{{ $event->name }}</td>
                    <td>{{ $nominee->unit }}</td>
                    <td>{{ $nominee->sub_unit }}</td>
                    <td>{{ $nominee->full_name }}</td>
                    <td>{{ $nominee->email }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="8" class="text-center text-muted py-4">No nominations found for this event.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endforeach

@endsection
