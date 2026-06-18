@extends('layouts.app')

@section('content')
<h2>Dashboard</h2>

<!-- Cards -->
<div class="row g-4 align-items-stretch">
    <!-- Card 1 -->
    <div class="col-12 col-sm-6 col-xl-3 d-flex">
        <div class="border-grey rounded-4 p-2 w-100 d-flex flex-column">
            <h3 class="card-title mb-1">New Events</h3>

            <div class="card border-0 flex-grow-1 d-flex flex-column">
                <div class="card-body mb-1 bg-light-orange rounded-4 rounded-bottom-0 d-flex flex-column flex-grow-1">
                    <div class="d-flex justify-content-between">
                        <p class="stat-value">{{ $newEventsCount }}</p>
                        <div class="icon-with-bg icon-bg-orange d-flex justify-content-center align-items-center">
                            <i class="bi bi-calendar2 text-white"></i>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle"></i>
                        <p>Currently Open</p>
                    </div>
                </div>

                <div class="card-footer text-center rounded-4 rounded-top-0 bg-light-orange border-0">
                    <a href="{{ route('nominator-active-events') }}" class="btn text-primary btn-link p-0 text-decoration-none fw-semibold">
                        View Events <i class="bi bi-arrow-up-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="col-12 col-sm-6 col-xl-3 d-flex">
        <div class="border-grey rounded-4 p-2 w-100 d-flex flex-column">
            <h3 class="card-title mb-1">Nominations</h3>

            <div class="card border-0 flex-grow-1 d-flex flex-column">
                <div class="card-body mb-1 bg-light-green rounded-4 rounded-bottom-0 d-flex flex-column flex-grow-1">
                    <div class="d-flex justify-content-between">
                        <p class="stat-value">{{ $nominationsCount }}</p>
                        <div class="icon-with-bg icon-bg-green d-flex justify-content-center align-items-center">
                            <i class="bi bi-people text-white"></i>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle"></i>
                        <p>Total Submitted</p>
                    </div>
                </div>

                <div class="card-footer text-center rounded-4 rounded-top-0 bg-light-green border-0">
                    <a href="{{ route('nominator-nominations') }}" class="btn text-primary btn-link p-0 text-decoration-none fw-semibold">
                        View Nominations <i class="bi bi-arrow-up-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="col-12 col-sm-6 col-xl-3 d-flex">
        <div class="border-grey rounded-4 p-2 w-100 d-flex flex-column">
            <h3 class="card-title mb-1">Event History</h3>

            <div class="card border-0 flex-grow-1 d-flex flex-column">
                <div class="card-body mb-1 bg-light-purple rounded-4 rounded-bottom-0 d-flex flex-column flex-grow-1">
                    <div class="d-flex justify-content-between">
                        <p class="stat-value">{{ $eventHistoryCount }}</p>
                        <div class="icon-with-bg icon-bg-purple d-flex justify-content-center align-items-center">
                            <i class="bi bi-clock-history text-white"></i>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle"></i>
                        <p>Completed Events</p>
                    </div>
                </div>

                <div class="card-footer text-center rounded-4 rounded-top-0 bg-light-purple border-0">
                    <a href="{{ route('nominator-completed-events') }}" class="btn text-primary btn-link p-0 text-decoration-none fw-semibold">
                        View History <i class="bi bi-arrow-up-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline -->
    <div class="col-12 col-sm-6 col-xl-3 d-flex">
        <div class="w-100 d-flex flex-column h-100 min-h-0">
            <h3 class="card-title mb-1">Recent Activity</h3>

            <div class="border rounded-4 px-4 flex-grow-1 overflow-y-auto overflow-x-hidden timeline-content-box scrollable-content-box">
                <section class="py-4">
                    <ul class="timeline-with-icons mb-0">
                        <li class="timeline-item mb-5">
                            <span class="timeline-icon d-flex justify-content-center align-items-center timeline-approved">
                                <i class="bi bi-check-lg"></i>
                            </span>

                            <p class="fw-bold">Nomination Approved</p>

                            <p class="text-secondary mb-2">
                                Sarah Miller's nomination for 'Innovation Prize' was approved.
                            </p>

                            <small class="text-light-grey fw-bold">
                                JUST NOW
                            </small>
                        </li>

                        <li class="timeline-item mb-5">
                            <span class="timeline-icon d-flex justify-content-center align-items-center timeline-draft">
                                <i class="bi bi-pencil text-warning"></i>
                            </span>

                            <p class="fw-bold">Draft Saved</p>

                            <p class="text-secondary mb-2">
                                You saved a draft for the 'Annual Sales Excellence' event.
                            </p>

                            <small class="text-light-grey fw-bold">
                                2 HOURS AGO
                            </small>
                        </li>

                        <li class="timeline-item">
                            <span class="timeline-icon d-flex justify-content-center align-items-center timeline-approved">
                                <i class="bi bi-check-lg"></i>
                            </span>

                            <p class="fw-bold">Event Published</p>

                            <p class="text-secondary mb-2">
                                Quarterly awards event has been published successfully.
                            </p>

                            <small class="text-light-grey fw-bold">
                                YESTERDAY
                            </small>
                        </li>
                    </ul>
                </section>
            </div>
        </div>
    </div>
</div>
<!-- Cards ends -->

<!-- Table -->
<div class="border mt-4 rounded-3 p-2">
    <div class="d-flex justify-content-between mb-2">
        <h3 class="card-title">Current Events</h3>
        <div class="d-flex align-items-center gap-3">
            <p class="mb-0 fw-light">{{ $events->count() }} Rows</p>

            <a href="{{ route('nominator-active-events') }}" class="btn text-primary btn-link p-0 text-decoration-none">
                View All
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-borderless">
            <thead>
                <tr class="table-light">
                    <th scope="col" style="min-width: 70px">Serial No.</th>
                    <th scope="col" style="min-width: 200px">Event Name</th>
                    <th scope="col" style="min-width: 120px">Start Date</th>
                    <th scope="col" style="min-width: 120px">End Date</th>
                    <th scope="col" style="min-width: 200px">Nominees</th>
                    <th scope="col" style="min-width: 100px">Nominations</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($events as $event)
                    <tr class="{{ $loop->iteration % 2 == 0 ? 'table-light' : '' }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $event->name }}</td>
                        <td>{{ $event->formatted_start_date }}</td>
                        <td>{{ $event->formatted_end_date }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-3 align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-people text-primary me-2"></i><span>{{ $event->nominees_count }}</span>
                                </div>
                                <div class="vr text-light-grey"></div>
                                <a href="{{ route('nominator-active-events') }}" class="btn btn-sm icon-bg-light-blue text-primary border-0 py-1 px-2 text-decoration-none">
                                    View List
                                </a>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('nominator-nominate-form', $event->id) }}" class="btn btn-primary btn-sm bg-primary border-0 text-white text-decoration-none">
                                Submit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No active events found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<!-- Table ends -->
@endsection