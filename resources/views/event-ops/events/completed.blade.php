@extends('layouts.app')

@section('content')
<h2 class="mb-0">Completed Events</h2>
<p class="text-secondary">Successfully Completed Events</p>

<!-- Filters -->
<div class="border rounded-3 p-2 my-3 bg-white">
  <form action="{{ route('event-ops-completed-events') }}" method="GET" class="d-flex align-items-center gap-3">
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
          <th scope="col" style="min-width: 120px">Event Code</th>
          <th scope="col" style="min-width: 200px">Event Name</th>
          <th scope="col" style="min-width: 200px">Assigned To</th>
          <th scope="col" style="min-width: 120px">Event Date</th>
          <th scope="col" style="min-width: 200px" class="text-center">Nominees</th>
        </tr>
      </thead>
      <tbody>
        @forelse($events as $index => $event)
          <tr class="{{ $index % 2 == 1 ? 'table-light' : '' }}">
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ $event->event_code }}</td>
            <td>
              <span class="fw-semibold">{{ $event->name }}</span>
              <p class="text-small text-light-grey lh-1 mb-0">{{ ucfirst(str_replace('_', ' ', $event->type)) }} Event</p>
            </td>
            <td>{{ $event->assigned_to }}</td>
            <td>{{ $event->formatted_start_date }}</td>
            <td class="text-center">
              <div class="d-inline-flex align-items-center gap-3">
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
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center text-muted py-4">No completed events found.</td>
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
                  <th scope="col" style="min-width: 120px">Sub Unit</th>
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
                    <td colspan="8" class="text-center text-muted py-4">No nominations found for this completed event.</td>
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
