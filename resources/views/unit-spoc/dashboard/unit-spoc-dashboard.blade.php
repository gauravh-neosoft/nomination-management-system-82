@extends('layouts.app')

@section('title', 'Unit SPOC - Dashboard')

@section('content')
  <h2>Dashboard</h2>
  <!-- Cards -->
  <div class="row g-4 align-items-stretch">
    <!-- Card 1 -->
    <div class="col-12 col-sm-6 col-xl-3 d-flex">
      <div class="rounded-4 p-2 w-100 d-flex flex-column card-top-border border-green">
        <div class="card border-0 flex-grow-1 d-flex flex-column">
          <div class="card-body mb-1 rounded-4 rounded-bottom-0 d-flex flex-column flex-grow-1">
            <div class="d-flex justify-content-between">
              <p class="stat-value text-green">{{ $totalNominations }}</p>

              <div class="icon-with-bg bg-light-green d-flex justify-content-center align-items-center">
                <i class="bi bi-people text-green dashboard-card-icon"></i>
              </div>
            </div>
          </div>

          <div class="card-footer border-0 bg-transparent">
            <p class="text-lg fw-semibold text-uppercase">
              Total nominations
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 2 -->
    <div class="col-12 col-sm-6 col-xl-3 d-flex">
      <div class="rounded-4 p-2 w-100 d-flex flex-column card-top-border border-purple">
        <div class="card border-0 flex-grow-1 d-flex flex-column">
          <div class="card-body mb-1 rounded-4 rounded-bottom-0 d-flex flex-column flex-grow-1">
            <div class="d-flex justify-content-between">
              <p class="stat-value text-purple">{{ $totalPending }}</p>

              <div class="icon-with-bg bg-light-purple d-flex justify-content-center align-items-center">
                <i class="bi bi-clock-history text-purple dashboard-card-icon"></i>
              </div>
            </div>
          </div>

          <div class="card-footer border-0 bg-transparent">
            <p class="text-lg fw-semibold text-uppercase">
              total pending
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 3 -->
    <div class="col-12 col-sm-6 col-xl-3 d-flex">
      <div class="rounded-4 p-2 w-100 d-flex flex-column card-top-border border-orange">
        <div class="card border-0 flex-grow-1 d-flex flex-column">
          <div class="card-body mb-1 rounded-4 rounded-bottom-0 d-flex flex-column flex-grow-1">
            <div class="d-flex justify-content-between">
              <p class="stat-value text-orange">{{ $reviewedToday }}</p>

              <div class="icon-with-bg bg-light-orange d-flex justify-content-center align-items-center">
                <i class="bi bi-calendar text-orange dashboard-card-icon"></i>
              </div>
            </div>
          </div>

          <div class="card-footer border-0 bg-transparent">
            <p class="text-lg fw-semibold text-uppercase">
              Reviewed today
            </p>
          </div>
        </div>
      </div>
    </div>


    <!-- Timeline -->
    <div class="col-12 col-sm-6 col-xl-3 d-flex">
      <div class="w-100 d-flex flex-column h-100 min-h-0">
        <h3 class="card-title mb-2 fs-6 fw-bold">Recent Activity</h3>

        <div class="border rounded-4 px-4 flex-grow-1 overflow-y-auto overflow-x-hidden timeline-content-box scrollable-content-box" style="max-height: 120px;">
          <section class="py-3">
            <ul class="timeline-with-icons mb-0">
              @forelse($recentActivities ?? [] as $activity)
                <li class="timeline-item mb-4">
                  <span class="timeline-icon d-flex justify-content-center align-items-center {{ $activity->approval_status === 'approved' ? 'timeline-approved' : ($activity->approval_status === 'rejected' ? 'timeline-declined' : 'timeline-draft') }}">
                    @if($activity->approval_status === 'approved')
                      <i class="bi bi-check-lg text-success"></i>
                    @elseif($activity->approval_status === 'rejected')
                      <i class="bi bi-x-lg text-danger"></i>
                    @else
                      <i class="bi bi-pencil text-warning"></i>
                    @endif
                  </span>

                  <p class="fw-bold mb-0 text-sm">Nomination {{ ucfirst($activity->approval_status) }}</p>
                  <p class="text-secondary mb-1 fs-08">
                    {{ $activity->first_name }} {{ $activity->last_name }}'s nomination for '{{ $activity->event_name }}' was {{ $activity->approval_status }}.
                  </p>
                  <small class="text-light-grey fw-bold text-uppercase fs-07">
                    {{ $activity->time_diff }}
                  </small>
                </li>
              @empty
                <li class="timeline-item">
                  <span class="timeline-icon d-flex justify-content-center align-items-center timeline-draft">
                    <i class="bi bi-info"></i>
                  </span>
                  <p class="text-secondary fs-08 mb-0">No recent activities found.</p>
                </li>
              @endforelse
            </ul>
          </section>
        </div>
      </div>
    </div>
  </div>
  <!-- Cards ends -->

  <!-- Table -->
  <div class="border rounded-3 p-2 mt-4">
    <div class="underline-tabs">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <ul class="nav nav-tabs border-0 mb-2" id="underlineTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active text-black fw-semibold" id="current-events-tab" data-bs-toggle="tab" data-bs-target="#current_events" type="button" role="tab">
              Current Events
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-black fw-semibold" id="pending-noms-tab" data-bs-toggle="tab" data-bs-target="#nominations_pending" type="button" role="tab">
              Nominations Pending
            </button>
          </li>
        </ul>

        <div class="d-flex align-items-center gap-3">
          <p class="mb-0 fw-light fs-09" id="rows-count-display">{{ count($currentEvents) }} Rows</p>

          <a href="{{ route('unit-spoc-active-events') }}" class="btn text-primary btn-link p-0 text-decoration-none fw-semibold fs-09">
            View All
          </a>
        </div>
      </div>

      <div class="tab-content mt-2" id="underlineTabsContent">
        <!-- Current Events Tab -->
        <div class="tab-pane fade show active" id="current_events" role="tabpanel" aria-labelledby="current-events-tab">
          <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0">
              <thead>
                <tr class="table-light">
                  <th scope="col" style="min-width: 100px" class="text-center fs-09 fw-semibold">Serial No.</th>
                  <th scope="col" style="min-width: 150px" class="fs-09 fw-semibold">Nominator</th>
                  <th scope="col" style="min-width: 250px" class="fs-09 fw-semibold">Event Name</th>
                  <th scope="col" style="min-width: 150px" class="text-center fs-09 fw-semibold">Status</th>
                  <th scope="col" style="min-width: 150px" class="text-center fs-09 fw-semibold">Nominations</th>
                </tr>
              </thead>
              <tbody>
                @forelse($currentEvents as $event)
                  <tr class="{{ $loop->iteration % 2 == 0 ? 'table-light' : '' }}">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $event->nominator_name }}</td>
                    <td>
                      <p class="lh-1 mb-1 fw-bold text-dark fs-09">{{ $event->name }}</p>
                      <small class="text-light-grey lh-1">{{ $event->type === 'hospitality' ? 'Hospitality' : 'Non-Hospitality' }} Event</small>
                    </td>
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
                    <td class="text-center">
                      <a href="{{ route('unit-spoc-nominations') }}" class="btn btn-primary btn-sm bg-primary border-0 rounded-3 px-3">
                        Submit
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center text-secondary py-4">No active events found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <!-- Nominations Pending Tab -->
        <div class="tab-pane fade" id="nominations_pending" role="tabpanel" aria-labelledby="pending-noms-tab">
          <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0">
              <thead>
                <tr class="table-light">
                  <th scope="col" style="min-width: 100px" class="text-center fs-09 fw-semibold">Serial No.</th>
                  <th scope="col" style="min-width: 150px" class="fs-09 fw-semibold">Nominator</th>
                  <th scope="col" style="min-width: 250px" class="fs-09 fw-semibold">Event Name</th>
                  <th scope="col" style="min-width: 150px" class="text-center fs-09 fw-semibold">Status</th>
                  <th scope="col" style="min-width: 200px" class="text-center fs-09 fw-semibold">Nominations</th>
                </tr>
              </thead>
              <tbody>
                @forelse($pendingNominationsEvents as $event)
                  <tr class="{{ $loop->iteration % 2 == 0 ? 'table-light' : '' }}">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $event->nominator_name }} {{ substr($event->nominator_last_name, 0, 1) }}.</td>
                    <td>
                      <p class="lh-1 mb-1 fw-bold text-dark fs-09">{{ $event->event_name }}</p>
                      <small class="text-light-grey lh-1">{{ $event->event_type === 'hospitality' ? 'Hospitality' : 'Non-Hospitality' }} Event</small>
                    </td>
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
                    <td class="text-center">
                      <div class="d-flex justify-content-center align-items-center gap-3">
                        <div class="d-flex align-items-center">
                          <i class="bi bi-people text-primary me-2"></i>
                          <span class="fw-semibold">{{ $event->pending_count }}</span>
                        </div>
                        <div class="vr text-light-grey" style="height: 18px;"></div>
                        <button class="btn btn-sm icon-bg-light-blue text-primary border-0 rounded-3 px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#pendingNomineesModal-{{ $event->event_id }}-{{ $event->nominator_id }}">
                          View List
                        </button>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center text-secondary py-4">No pending nominations found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Table ends -->

  <!-- Modals for Pending Nominees Lists -->
  @foreach($pendingNominationsEvents as $event)
    <div class="modal fade" id="pendingNomineesModal-{{ $event->event_id }}-{{ $event->nominator_id }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header justify-content-between align-items-center">
            <h3 class="modal-title fs-5 fw-bold">
              Nominations list - {{ $event->event_name }}
            </h3>
            <div class="d-flex align-items-center gap-3">
              <button type="button" class="btn btn-secondary btn-sm rounded-3">
                <i class="bi bi-x me-2"></i> Bulk Reject
              </button>
              <button type="button" class="btn btn-primary btn-sm rounded-3">
                <i class="bi bi-check2 me-2"></i> Bulk Approve
              </button>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
          </div>
          <div class="modal-body">
            <!-- Status Filters matching the design -->
            <div class="mb-3 d-flex gap-2 align-items-center flex-wrap">
              <p class="text-secondary mb-0">Status:</p>
              <button class="btn bg-light-green text-success px-4 py-1 rounded-3 fs-09 border border-success border-opacity-25" style="pointer-events: none; opacity: 0.6;">
                Approved
              </button>
              <button class="btn bg-light-warning text-warning px-4 py-1 rounded-3 fs-09 border border-warning">
                Pending
              </button>
              <button class="btn bg-light-orange text-orange px-4 py-1 rounded-3 fs-09 border border-danger border-opacity-25" style="pointer-events: none; opacity: 0.6;">
                Rejected
              </button>
            </div>

            <div class="table-responsive">
              <table class="table table-borderless align-middle mb-0">
                <thead>
                  <tr class="table-light">
                    <th scope="col" style="width: 40px">
                      <div class="form-check">
                        <input class="form-check-input border-primary select-all-checkbox" type="checkbox" />
                      </div>
                    </th>
                    <th scope="col" style="min-width: 100px" class="text-center">Serial No.</th>
                    <th scope="col" style="min-width: 150px">GDPR Compliant</th>
                    <th scope="col" style="min-width: 200px">Event Name</th>
                    <th scope="col" style="min-width: 120px">Unit</th>
                    <th scope="col" style="min-width: 120px">Sub Unit</th>
                    <th scope="col" style="min-width: 200px">Name</th>
                    <th scope="col" style="min-width: 200px">Email</th>
                    <th scope="col" style="min-width: 150px" class="text-center">Company</th>
                    <th scope="col" style="min-width: 120px">Job Title</th>
                    <th scope="col" style="min-width: 200px">Primary AM Name</th>
                    <th scope="col" style="min-width: 200px">Primary AM Email ID</th>
                    <th scope="col" style="min-width: 200px">Account Manager Email ID</th>
                    <th scope="col" style="min-width: 200px">Account Manager Email ID 2</th>
                    <th scope="col" style="min-width: 150px">Business/IT</th>
                    <th scope="col" style="min-width: 150px">Invite Status</th>
                    <th scope="col" style="min-width: 150px" class="text-center">Approval Status</th>
                    <th scope="col" style="min-width: 150px" class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($event->nominees ?? [] as $nominee)
                    <tr class="{{ $loop->iteration % 2 == 0 ? 'table-light' : '' }}">
                      <td>
                        <div class="form-check">
                          <input class="form-check-input row-checkbox border-primary" type="checkbox" value="{{ $nominee->id }}" />
                        </div>
                      </td>
                      <td class="text-center">{{ $loop->iteration }}</td>
                      <td>{{ $nominee->gdpr_compliance }}</td>
                      <td>{{ $event->event_name }}</td>
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
                      <td>{{ $nominee->invite_status }}</td>
                      <td>
                        <div class="d-flex justify-content-center align-items-center">
                          <a href="{{ route('unit-spoc-nominations') }}?event_id={{ $event->event_id }}&action=approve&id={{ $nominee->id }}" class="btn p-0 border-0 bg-transparent" title="Approve">
                            <i class="bi bi-check-circle text-green fs-5"></i>
                          </a>
                          <div class="vr text-light-grey mx-2" style="height: 16px;"></div>
                          <a href="{{ route('unit-spoc-nominations') }}?event_id={{ $event->event_id }}&action=reject&id={{ $nominee->id }}" class="btn p-0 border-0 bg-transparent" title="Reject">
                            <i class="bi bi-x-circle text-danger fs-5"></i>
                          </a>
                        </div>
                      </td>
                      <td class="text-center">
                        <a href="{{ route('unit-spoc-nominations') }}?event_id={{ $event->event_id }}&action=edit&id={{ $nominee->id }}" class="btn btn-link text-primary text-decoration-none fw-semibold p-0">
                          Edit
                        </a>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="18" class="text-center text-secondary py-4">No nominee records found for this event.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer justify-content-end">
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
      const currentEventsCount = {{ count($currentEvents) }};
      const pendingEventsCount = {{ count($pendingNominationsEvents) }};
      const countDisplay = document.getElementById('rows-count-display');

      document.getElementById('current-events-tab').addEventListener('shown.bs.tab', function () {
        countDisplay.textContent = currentEventsCount + ' Rows';
      });

      document.getElementById('pending-noms-tab').addEventListener('shown.bs.tab', function () {
        countDisplay.textContent = pendingEventsCount + ' Rows';
      });

      // Select All checkbox logic in modals
      document.querySelectorAll('.modal').forEach(modal => {
        const selectAllCheckbox = modal.querySelector('.select-all-checkbox');
        if (selectAllCheckbox) {
          selectAllCheckbox.addEventListener('change', function () {
            const checkboxes = modal.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => {
              cb.checked = selectAllCheckbox.checked;
            });
          });
        }
      });
    });
  </script>
@endpush