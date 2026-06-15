@extends('layouts.app')

@section('content')
<!-- ========================================== -->
<!-- 1. MAIN DASHBOARD OVERVIEW PANEL -->
<!-- ========================================== -->
<div id="panel-dashboard" class="admin-panel">
  <h2>Dashboard</h2>
  <p class="text-secondary fs-09 mb-4">Nomination platform performance and activity overview.</p>
  
  <!-- Dashboard Cards Grid -->
  <div class="row g-4 align-items-stretch">
    <!-- Card 1: Active Events -->
    <div class="col-12 col-sm-6 col-xl-3 d-flex">
      <div class="border-grey rounded-4 p-2 w-100 d-flex flex-column bg-white">
        <p class="sub-heading fw-semibold lh-1 p-2">Active Events</p>
        <div class="card border-0 flex-grow-1 d-flex flex-column">
          <div class="card-body mb-1 bg-light-orange rounded-4 rounded-bottom-0 d-flex flex-column flex-grow-1">
            <div class="d-flex justify-content-between">
              <h2 class="card-title fw-bold" id="metric-active-events">15</h2>
              <div class="icon-with-bg icon-bg-orange d-flex justify-content-center align-items-center">
                <i class="bi bi-calendar2-check text-white"></i>
              </div>
            </div>
            <p class="card-subtitle text-muted flex-grow-1 mt-2">
              <i class="bi bi-info-circle"></i>
              <span class="fs-09">2 Ending This Week</span>
            </p>
          </div>
          <div class="card-footer text-center rounded-4 rounded-top-0 bg-light-orange border-0">
            <a href="{{ route('admin-events') }}" class="btn btn-link p-0 text-decoration-none fw-semibold fs-09">
              Manage Events <i class="bi bi-arrow-up-right"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 2: Nominators Listed -->
    <div class="col-12 col-sm-6 col-xl-3 d-flex">
      <div class="border-grey rounded-4 p-2 w-100 d-flex flex-column bg-white">
        <p class="sub-heading fw-semibold lh-1 p-2">Nominators Listed</p>
        <div class="card border-0 flex-grow-1 d-flex flex-column">
          <div class="card-body mb-1 bg-light-green rounded-4 rounded-bottom-0 d-flex flex-column flex-grow-1">
            <div class="d-flex justify-content-between">
              <h2 class="card-title fw-bold" id="metric-nominators">48</h2>
              <div class="icon-with-bg icon-bg-green d-flex justify-content-center align-items-center">
                <i class="bi bi-people text-white"></i>
              </div>
            </div>
            <p class="card-subtitle text-muted flex-grow-1 mt-2">
              <i class="bi bi-info-circle"></i>
              <span class="fs-09">4 Access tiers configured</span>
            </p>
          </div>
          <div class="card-footer text-center rounded-4 rounded-top-0 bg-light-green border-0">
            <a href="{{ route('admin-users') }}" class="btn btn-link p-0 text-decoration-none fw-semibold text-nowrap fs-09">
              View Directory <i class="bi bi-arrow-up-right"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 3: Applications Received -->
    <div class="col-12 col-sm-6 col-xl-3 d-flex">
      <div class="border-grey rounded-4 p-2 w-100 d-flex flex-column bg-white">
        <p class="sub-heading fw-semibold lh-1 p-2">Applications Received</p>
        <div class="card border-0 flex-grow-1 d-flex flex-column">
          <div class="card-body mb-1 bg-light-purple rounded-4 rounded-bottom-0 d-flex flex-column flex-grow-1">
            <div class="d-flex justify-content-between">
              <h2 class="card-title fw-bold" id="metric-applications">184</h2>
              <div class="icon-with-bg icon-bg-purple d-flex justify-content-center align-items-center">
                <i class="bi bi-file-earmark-text text-white"></i>
              </div>
            </div>
            <p class="card-subtitle text-muted flex-grow-1 mt-2">
              <i class="bi bi-info-circle"></i>
              <span class="fs-09">34 Pending Review State</span>
            </p>
          </div>
          <div class="card-footer text-center rounded-4 rounded-top-0 bg-light-purple border-0">
            <a href="{{ route('admin-queue') }}" class="btn btn-link p-0 text-decoration-none fw-semibold fs-09">
              Monitor Queue <i class="bi bi-arrow-up-right"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 4: Recent Admin Activities -->
    <div class="col-12 col-sm-6 col-xl-3 d-flex">
      <div class="w-100 d-flex flex-column h-100 min-h-0 bg-white">
        <p class="fw-bold lh-1 mb-2">Recent System Logs</p>
        <div class="border rounded-4 px-3 py-2 flex-grow-1 overflow-y-auto overflow-x-hidden timeline-content-box" style="max-height: 160px;">
          <ul class="timeline-with-icons mb-0" style="padding-left: 20px; font-size: 0.85rem;">
            <li class="timeline-item mb-2" style="list-style-type: none; position: relative;">
              <span class="timeline-icon d-flex justify-content-center align-items-center timeline-approved" style="position: absolute; left: -25px; top: 2px;">
                <i class="bi bi-check-lg text-success"></i>
              </span>
              <p class="fw-bold mb-0">Domain Limit Updated</p>
              <small class="text-secondary">Cisco domain cap increased to 10.</small>
            </li>
            <li class="timeline-item mb-2" style="list-style-type: none; position: relative;">
              <span class="timeline-icon d-flex justify-content-center align-items-center timeline-draft" style="position: absolute; left: -25px; top: 2px;">
                <i class="bi bi-pencil text-warning"></i>
              </span>
              <p class="fw-bold mb-0">Role Permission Changed</p>
              <small class="text-secondary">Unit SPOC edit permission activated.</small>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Summary Details Table Area -->
  <div class="border mt-4 rounded-3 p-3 bg-white">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <p class="fw-bold lh-1 mb-0">System Activity Monitoring</p>
      <span class="badge bg-secondary">Live Status</span>
    </div>
    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle">
        <thead>
          <tr class="table-light">
            <th class="fs-09 fw-semibold">Task Area</th>
            <th class="fs-09 fw-semibold">Description</th>
            <th class="fs-09 fw-semibold">Access Level</th>
            <th class="fs-09 fw-semibold">Operational Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Role Assignment</td>
            <td>Group mapping and access security</td>
            <td>Admin Only</td>
            <td><span class="badge bg-success bg-opacity-75 text-white">Configured</span></td>
          </tr>
          <tr>
            <td>Exclusion Rules</td>
            <td>Blocklists checking and validation engine</td>
            <td>Event OPS / Admin</td>
            <td><span class="badge bg-success bg-opacity-75 text-white">Active</span></td>
          </tr>
          <tr>
            <td>Domain Boundaries</td>
            <td>Infosys, NeoSoft, Adobe caps</td>
            <td>Admin Only</td>
            <td><span class="badge bg-warning text-dark">Monitored</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection