@extends('layouts.app')

@section('content')
<h2 class="mb-0">Active Events</h2>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
  <p class="text-secondary mb-0">Events currently open for nomination submission</p>
  <div>
    <span class="badge fw-normal rounded-pill bg-light-green text-success px-3 py-2 me-2">
      {{ $openCount }} Open
    </span>
    <span class="badge fw-normal rounded-pill px-3 py-2" style="background-color: #fff9e6; color: #d9a300;">
      {{ $closingSoonCount }} Closing soon
    </span>
  </div>
</div>

<!-- Filters -->
<div class="border rounded-3 p-2 my-3 bg-white">
  <form action="{{ route('event-ops-active-events') }}" method="GET" class="d-flex align-items-center gap-3">
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
          <th scope="col" style="min-width: 100px" class="text-center">Serial No.</th>
          <th scope="col" style="min-width: 120px">Event Code</th>
          <th scope="col" style="min-width: 100px" class="text-center">Status</th>
          <th scope="col" style="min-width: 200px" class="text-start">Event Name</th>
          <th scope="col" style="min-width: 200px" class="text-center">Timeline</th>
          <th scope="col" style="min-width: 150px" class="text-center">Location</th>
          <th scope="col" style="min-width: 120px" class="text-center">Limitations</th>
          <th scope="col" style="min-width: 250px" class="text-center">Nominations</th>
        </tr>
      </thead>
      <tbody>
        @forelse($events as $index => $event)
          <tr class="{{ $index % 2 == 1 ? 'table-light' : '' }}">
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ $event->event_code }}</td>
            <td class="text-center">
              @if($event->is_closing_soon)
                <div class="w-fit mx-auto badge rounded-pill closing-soon px-2 py-1 fw-normal d-flex align-items-center gap-2" style="background-color: #fff9e6; color: #d9a300;">
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
            <td class="text-start">
              <span class="fw-semibold">{{ $event->name }}</span>
              <p class="text-small text-light-grey lh-1 mb-0">{{ ucfirst(str_replace('_', ' ', $event->type)) }} Event</p>
            </td>
            <td class="text-center">{{ $event->formatted_start_date }} - {{ $event->formatted_end_date }}</td>
            <td class="text-center">{{ $event->location }}</td>
            <td class="text-center">{{ $event->max_nominees_per_form }}</td>
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
                <div class="vr text-light-grey"></div>
                <a href="{{ route('event-ops-event-nominations', $event->id) }}" class="btn btn-sm btn-link text-decoration-none py-1">
                  Manage
                </a>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center text-muted py-4">No active events found.</td>
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
          <div class="d-flex align-items-center gap-2">
            <a href="{{ route('event-ops-event-nominations', $event->id) }}" class="btn btn-primary btn-sm rounded-3">
              <i class="bi bi-gear-fill me-2"></i>Manage Nominations
            </a>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
        </div>
        <div class="modal-body">
          <div class="underline-tabs">
            <ul class="nav nav-tabs border-0 mb-3" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active text-black" data-bs-toggle="tab" data-bs-target="#pending-{{ $event->id }}" type="button" role="tab">
                  Nomination Pending
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link text-black" data-bs-toggle="tab" data-bs-target="#reviewed-{{ $event->id }}" type="button" role="tab">
                  Nominations Reviewed
                </button>
              </li>
            </ul>

            <div class="tab-content">
              <!-- Pending Tab -->
              <div class="tab-pane fade show active" id="pending-{{ $event->id }}" role="tabpanel">
                <div class="table-responsive">
                  <table class="table table-borderless align-middle">
                    <thead>
                      <tr class="table-light">
                        <th scope="col" class="text-center" style="min-width: 70px">Serial No.</th>
                        <th scope="col" style="min-width: 130px">Invite Status</th>
                        <th scope="col" style="min-width: 150px">GDPR Compliant</th>
                        <th scope="col" style="min-width: 100px">Unit</th>
                        <th scope="col" style="min-width: 100px">Sub Unit</th>
                        <th scope="col" style="min-width: 150px">Name</th>
                        <th scope="col" style="min-width: 200px">Email</th>
                        <th scope="col" style="min-width: 120px">Company</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php $pIdx = 1; @endphp
                      @forelse($event->nominees->where('invite_status', 'Invite Pending') as $nominee)
                        <tr>
                          <td class="text-center">{{ $pIdx++ }}</td>
                          <td>
                            <span class="badge bg-light-yellow text-warning rounded-pill fw-normal px-2 py-1">Pending</span>
                          </td>
                          <td>{{ $nominee->gdpr_compliance }}</td>
                          <td>{{ $nominee->unit }}</td>
                          <td>{{ $nominee->sub_unit }}</td>
                          <td>{{ $nominee->full_name }}</td>
                          <td>{{ $nominee->email }}</td>
                          <td>{{ $nominee->company }}</td>
                        </tr>
                      @empty
                        <tr>
                          <td colspan="8" class="text-center text-muted py-4">No pending nominations for this event.</td>
                        </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Reviewed Tab -->
              <div class="tab-pane fade" id="reviewed-{{ $event->id }}" role="tabpanel">
                <div class="table-responsive">
                  <table class="table table-borderless align-middle">
                    <thead>
                      <tr class="table-light">
                        <th scope="col" class="text-center" style="min-width: 70px">Serial No.</th>
                        <th scope="col" style="min-width: 130px">Invite Status</th>
                        <th scope="col" style="min-width: 150px">GDPR Compliant</th>
                        <th scope="col" style="min-width: 100px">Unit</th>
                        <th scope="col" style="min-width: 100px">Sub Unit</th>
                        <th scope="col" style="min-width: 150px">Name</th>
                        <th scope="col" style="min-width: 200px">Email</th>
                        <th scope="col" style="min-width: 120px">Company</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php $rIdx = 1; @endphp
                      @forelse($event->nominees->where('invite_status', '!=', 'Invite Pending') as $nominee)
                        <tr>
                          <td class="text-center">{{ $rIdx++ }}</td>
                          <td>
                            <span class="badge bg-light-green text-success rounded-pill fw-normal px-2 py-1">
                              {{ $nominee->invite_status }}
                            </span>
                          </td>
                          <td>{{ $nominee->gdpr_compliance }}</td>
                          <td>{{ $nominee->unit }}</td>
                          <td>{{ $nominee->sub_unit }}</td>
                          <td>{{ $nominee->full_name }}</td>
                          <td>{{ $nominee->email }}</td>
                          <td>{{ $nominee->company }}</td>
                        </tr>
                      @empty
                        <tr>
                          <td colspan="8" class="text-center text-muted py-4">No reviewed nominations for this event.</td>
                        </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endforeach

@endsection
