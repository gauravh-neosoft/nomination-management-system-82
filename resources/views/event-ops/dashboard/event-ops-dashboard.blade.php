@extends('layouts.app')

@section('content')
<h2>Dashboard</h2>

<!-- Cards -->
<div class="row g-4 align-items-stretch">
  <div class="col-12 col-sm-8">
    <div class="row row-gap-3">
      <!-- Card 1 -->
      <div class="col-12 col-md-6">
        <div class="rounded-4 p-2 w-100 bg-light-orange">
          <div class="bg-white p-3 rounded-4">
            <div class="d-flex justify-content-between align-items-end mb-4">
              <p class="stat-value">{{ $eventsCount }}</p>
              <p class="fw-bold">Active Events</p>
            </div>
            <div class="d-flex justify-content-between">
              <a href="{{ route('event-ops-active-events') }}" class="btn text-primary btn-link p-0 text-decoration-none fw-semibold">
                View All <i class="bi bi-arrow-up-right"></i>
              </a>
              <i class="bi bi-calendar2 text-orange dashboard-card-icon"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="col-12 col-md-6">
        <div class="rounded-4 p-2 w-100 bg-light-green">
          <div class="bg-white p-3 rounded-4">
            <div class="d-flex justify-content-between align-items-end mb-4">
              <p class="stat-value">{{ $nominationsCount }}</p>
              <p class="fw-bold">Nominations</p>
            </div>
            <div class="d-flex justify-content-between">
              <a href="{{ route('event-ops-nominations') }}" class="btn text-primary btn-link p-0 text-decoration-none fw-semibold">
                View All <i class="bi bi-arrow-up-right"></i>
              </a>
              <i class="bi bi-people dashboard-card-icon text-success"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="col-12 col-md-6">
        <div class="rounded-4 p-2 w-100 bg-light-purple">
          <div class="bg-white p-3 rounded-4">
            <div class="d-flex justify-content-between align-items-end mb-4">
              <p class="stat-value">{{ $pendingCount }}</p>
              <p class="fw-bold">Nominations Pending</p>
            </div>
            <div class="d-flex justify-content-between">
              <a href="{{ route('event-ops-nominations') }}" class="btn text-primary btn-link p-0 text-decoration-none fw-semibold">
                View All <i class="bi bi-arrow-up-right"></i>
              </a>
              <i class="bi bi-clock-history text-purple dashboard-card-icon"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 4 -->
      <div class="col-12 col-md-6">
        <div class="rounded-4 p-2 w-100 bg-light-warning">
          <div class="bg-white p-3 rounded-4">
            <div class="d-flex justify-content-between align-items-end mb-4">
              <p class="stat-value">{{ $reviewedCount }}</p>
              <p class="fw-bold">Nominations Reviewed</p>
            </div>
            <div class="d-flex justify-content-between">
              <a href="{{ route('event-ops-nominations') }}" class="btn text-primary btn-link p-0 text-decoration-none fw-semibold">
                View All <i class="bi bi-arrow-up-right"></i>
              </a>
              <i class="bi bi-clipboard-check text-yellow dashboard-card-icon"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Timeline -->
  <div class="col-12 col-sm-4">
    <div class="w-100 d-flex flex-column h-100 min-h-0">
      <h3 class="card-title mb-1">Recent Activity</h3>
      <div class="border rounded-4 px-4 flex-grow-1 overflow-y-auto overflow-x-hidden timeline-content-box scrollable-content-box" style="max-height: 280px;">
        <section class="py-4">
          <ul class="timeline-with-icons mb-0">
            @forelse($recentActivities as $activity)
              <li class="timeline-item mb-4">
                @if($activity->approval_status === 'approved')
                  <span class="timeline-icon d-flex justify-content-center align-items-center timeline-approved">
                    <i class="bi bi-check-lg text-white"></i>
                  </span>
                @else
                  <span class="timeline-icon d-flex justify-content-center align-items-center timeline-draft">
                    <i class="bi bi-pencil text-warning"></i>
                  </span>
                @endif
                <p class="fw-bold mb-0">{{ $activity->approval_status === 'approved' ? 'Nomination Approved' : 'Nomination Updated' }}</p>
                <p class="text-secondary mb-1 text-small">
                  {{ $activity->first_name }} {{ $activity->last_name }}'s nomination for '{{ $activity->event_name }}' was processed.
                </p>
                <small class="text-light-grey fw-bold text-uppercase">{{ $activity->formatted_time }}</small>
              </li>
            @empty
              <li class="text-muted text-center py-4">No recent activity.</li>
            @endforelse
          </ul>
        </section>
      </div>
    </div>
  </div>
</div>
<!-- Cards ends -->

<!-- Table -->
<div class="border mt-4 rounded-3 p-2">
  <div class="d-flex justify-content-between mb-2 align-items-center">
    <h3 class="card-title">Current Events</h3>
    <div class="d-flex align-items-center gap-3">
      <p class="mb-0 fw-light">{{ $events->count() }} Rows</p>
      <a href="{{ route('event-ops-active-events') }}" class="btn text-primary btn-link p-0 text-decoration-none">
        View All
      </a>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table table-borderless align-middle">
      <thead>
        <tr class="table-light">
          <th scope="col" style="min-width: 70px" class="text-center">Serial No.</th>
          <th scope="col" style="min-width: 120px">Event Code</th>
          <th scope="col" style="min-width: 200px">Event Name</th>
          <th scope="col" style="min-width: 120px">Event Date</th>
          <th scope="col" style="min-width: 120px">Nomination Deadline</th>
          <th scope="col" style="min-width: 120px" class="text-center">Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($events as $index => $event)
          <tr class="{{ $index % 2 == 1 ? 'table-light' : '' }}">
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ $event->event_code }}</td>
            <td>{{ $event->name }}</td>
            <td>{{ $event->formatted_start_date }}</td>
            <td>{{ $event->formatted_deadline }}</td>
            <td>
              @if($event->status_badge === 'Open')
                <div class="w-fit mx-auto badge rounded-pill bg-light-green text-success px-2 py-1 fw-normal d-flex align-items-center gap-2">
                  <span class="rounded-circle bg-success d-inline-block" style="width: 7px; height: 7px"></span>
                  Open
                </div>
              @elseif($event->status_badge === 'Closing soon')
                <div class="w-fit mx-auto badge rounded-pill closing-soon px-2 py-1 fw-normal d-flex align-items-center gap-2" style="background-color: #fff9e6; color: #d9a300;">
                  <span class="rounded-circle bg-warning d-inline-block" style="width: 7px; height: 7px"></span>
                  Closing soon
                </div>
              @else
                <div class="w-fit mx-auto badge rounded-pill bg-light text-secondary px-2 py-1 fw-normal d-flex align-items-center gap-2">
                  <span class="rounded-circle bg-secondary d-inline-block" style="width: 7px; height: 7px"></span>
                  Closed
                </div>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center text-muted py-4">No ongoing events found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<!-- Table ends -->
@endsection