@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/nominator-dashboard.css') }}">
@endpush

@section('content')

<div class="dashboard-container nominator-dashboard-page">

    <!-- Top section: Stats & Recent Activities -->
    <div class="row g-3 mb-4">
        
        <!-- Left: Stats Cards Grid -->
        <div class="col-xl-9 col-lg-8">
            <div class="row g-3">
                
                <!-- Card 1: New Events -->
                <div class="col-md-4">
                    <div class="stat-card card-orange">
                        <div class="stat-card-header d-flex justify-content-between align-items-start">
                            <div>
                                <span class="stat-label">New Events</span>
                                <h2 class="stat-number">12</h2>
                            </div>
                            <div class="stat-icon-wrapper bg-orange-light">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                        </div>
                        <div class="stat-card-body mt-2">
                            <span class="stat-info-text">
                                <i class="bi bi-clock"></i> 2 Closing Soon
                            </span>
                        </div>
                        <div class="stat-card-footer mt-3">
                            <a href="#" class="stat-link">View Events <i class="bi bi-arrow-up-right ms-1"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Nominations -->
                <div class="col-md-4">
                    <div class="stat-card card-teal">
                        <div class="stat-card-header d-flex justify-content-between align-items-start">
                            <div>
                                <span class="stat-label">Nominations</span>
                                <h2 class="stat-number">28</h2>
                            </div>
                            <div class="stat-icon-wrapper bg-teal-light">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                        <div class="stat-card-body mt-2">
                            <span class="stat-info-text">
                                <i class="bi bi-clock"></i> 3 Pending Review
                            </span>
                        </div>
                        <div class="stat-card-footer mt-3">
                            <a href="#" class="stat-link">View Nominations <i class="bi bi-arrow-up-right ms-1"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Event History -->
                <div class="col-md-4">
                    <div class="stat-card card-purple">
                        <div class="stat-card-header d-flex justify-content-between align-items-start">
                            <div>
                                <span class="stat-label">Event History</span>
                                <h2 class="stat-number">45</h2>
                            </div>
                            <div class="stat-icon-wrapper bg-purple-light">
                                <i class="bi bi-clock-history"></i>
                            </div>
                        </div>
                        <div class="stat-card-body mt-2">
                            <span class="stat-info-text">
                                <i class="bi bi-clock"></i> Last Activity: 2 days ago
                            </span>
                        </div>
                        <div class="stat-card-footer mt-3">
                            <a href="#" class="stat-link">View History <i class="bi bi-arrow-up-right ms-1"></i></a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Right: Recent Activities -->
        <div class="col-xl-3 col-lg-4">
            <div class="activity-panel h-100 d-flex flex-column">
                <div class="activity-header d-flex justify-content-between align-items-center mb-3">
                    <h6 class="m-0 font-bold text-dark">Recent Activities</h6>
                    <a href="#" class="view-all-link">View All</a>
                </div>
                <div class="activity-list flex-grow-1">
                    
                    <div class="activity-item d-flex gap-3 align-items-start py-2">
                        <div class="activity-icon bg-success-light">
                            <i class="bi bi-check-lg text-success"></i>
                        </div>
                        <div class="activity-details">
                            <h6 class="activity-title mb-1 text-dark">Nomination Approved</h6>
                            <p class="activity-desc mb-1">Sarah Miller's nomination for 'Innovation Prize' was approved.</p>
                            <small class="activity-time">JUST NOW</small>
                        </div>
                    </div>

                    <div class="activity-item d-flex gap-3 align-items-start py-2">
                        <div class="activity-icon bg-warning-light">
                            <i class="bi bi-pencil-fill text-warning"></i>
                        </div>
                        <div class="activity-details">
                            <h6 class="activity-title mb-1 text-dark">Draft Saved</h6>
                            <p class="activity-desc mb-1">You saved a draft for the 'Annual Sales Excellence' event.</p>
                            <small class="activity-time">2 HOURS AGO</small>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- Bottom section: Current Events Table -->
    <div class="dashboard-table-container">
        
        <div class="table-header d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0 font-bold text-dark">Current Events</h5>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small font-semibold">24 Rows</span>
                <a href="#" class="view-all-link">View All</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">Serial No.</th>
                        <th scope="col">Event Name</th>
                        <th scope="col">Event Date</th>
                        <th scope="col">Nomination Deadline</th>
                        <th scope="col" class="text-center">Nominees</th>
                        <th scope="col" class="text-center">Nominations</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 1; $i <= 7; $i++)
                    <tr>
                        <td class="text-center text-muted font-medium">{{ $i }}</td>
                        <td class="event-name font-semibold">Q3 Performance Awards</td>
                        <td>Dec 1, 2024</td>
                        <td>Dec 14, 2024</td>
                        <td class="text-center">
                            <span class="nominee-count-badge me-2">
                                <i class="bi bi-people text-primary-light"></i> {{ 24 }}
                            </span>
                            <a href="#" class="btn btn-view-list btn-sm">View List</a>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-submit-nomination btn-sm">Submit</button>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>

    </div>

</div>

@endsection