@extends('layouts.app')

@section('content')
<h2>Dashboard</h2>
<!-- Dashboard Cards Grid -->
<div class="row g-4 align-items-stretch">
  <!-- Card 1: New Events -->
  <div class="col-12 col-sm-6 col-xl-3 d-flex">
    <div class="border-grey rounded-4 p-2 w-100 d-flex flex-column">
      <p class="sub-heading fw-semibold lh-1 p-2">New Events</p>

      <div class="card border-0 flex-grow-1 d-flex flex-column">
        <div class="card-body mb-1 bg-light-orange rounded-4 rounded-bottom-0 d-flex flex-column flex-grow-1">
          <div class="d-flex justify-content-between">
            <h2 class="card-title fw-bold">12</h2>
            <div class="icon-with-bg icon-bg-orange d-flex justify-content-center align-items-center">
              <i class="bi bi-calendar2 text-white"></i>
            </div>
          </div>
          <p class="card-subtitle text-muted flex-grow-1">
            <i class="bi bi-info-circle"></i>
            <span class="fs-09">2 Closing Soon</span>
          </p>
        </div>

        <div class="card-footer text-center rounded-4 rounded-top-0 bg-light-orange border-0">
          <button type="button" class="btn btn-link p-0 text-decoration-none fw-semibold fs-09">
            View Events <i class="bi bi-arrow-up-right"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Card 2: Nominations -->
  <div class="col-12 col-sm-6 col-xl-3 d-flex">
    <div class="border-grey rounded-4 p-2 w-100 d-flex flex-column">
      <p class="sub-heading fw-semibold lh-1 p-2">Nominations</p>

      <div class="card border-0 flex-grow-1 d-flex flex-column">
        <div class="card-body mb-1 bg-light-green rounded-4 rounded-bottom-0 d-flex flex-column flex-grow-1">
          <div class="d-flex justify-content-between">
            <h2 class="card-title fw-bold">28</h2>
            <div class="icon-with-bg icon-bg-green d-flex justify-content-center align-items-center">
              <i class="bi bi-people text-white"></i>
            </div>
          </div>
          <p class="card-subtitle text-muted flex-grow-1">
            <i class="bi bi-info-circle"></i>
            <span class="fs-09">3 Pending Review</span>
          </p>
        </div>

        <div class="card-footer text-center rounded-4 rounded-top-0 bg-light-green border-0">
          <button type="button" class="btn btn-link p-0 text-decoration-none fw-semibold text-nowrap fs-09">
            View Nominations <i class="bi bi-arrow-up-right"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Card 3: Event History -->
  <div class="col-12 col-sm-6 col-xl-3 d-flex">
    <div class="border-grey rounded-4 p-2 w-100 d-flex flex-column">
      <p class="sub-heading fw-semibold lh-1 p-2">Event History</p>

      <div class="card border-0 flex-grow-1 d-flex flex-column">
        <div class="card-body mb-1 bg-light-purple rounded-4 rounded-bottom-0 d-flex flex-column flex-grow-1">
          <div class="d-flex justify-content-between">
            <h2 class="card-title fw-bold">45</h2>
            <div class="icon-with-bg icon-bg-purple d-flex justify-content-center align-items-center">
              <i class="bi bi-clock-history text-white"></i>
            </div>
          </div>
          <p class="card-subtitle text-muted flex-grow-1">
            <i class="bi bi-info-circle"></i>
            <span class="fs-09">Last Activity: 2 days ago</span>
          </p>
        </div>

        <div class="card-footer text-center rounded-4 rounded-top-0 bg-light-purple border-0">
          <button type="button" class="btn btn-link p-0 text-decoration-none fw-semibold fs-09">
            View History <i class="bi bi-arrow-up-right"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Card 4: Recent Activity Timeline -->
  <div class="col-12 col-sm-6 col-xl-3 d-flex">
    <div class="w-100 d-flex flex-column h-100 min-h-0">
      <p class="fw-bold lh-1 mb-2">Recent Activity</p>

      <div class="border rounded-4 px-4 flex-grow-1 overflow-y-auto overflow-x-hidden timeline-content-box">
        <section class="py-4">
          <ul class="timeline-with-icons mb-0">
            <li class="timeline-item mb-5">
              <span class="timeline-icon d-flex justify-content-center align-items-center timeline-approved">
                <i class="bi bi-check-lg"></i>
              </span>
              <p class="fw-bold mb-0 fs-6">Nomination Approved</p>
              <p class="text-secondary fs-09 mb-0">
                Sarah Miller's nomination for 'Innovation Prize' was approved.
              </p>
              <small class="text-light-grey fw-semibold">JUST NOW</small>
            </li>

            <li class="timeline-item mb-5">
              <span class="timeline-icon d-flex justify-content-center align-items-center timeline-draft">
                <i class="bi bi-pencil text-warning"></i>
              </span>
              <p class="fw-bold mb-0 fs-6">Draft Saved</p>
              <p class="text-secondary fs-09 mb-0">
                You saved a draft for the 'Annual Sales Excellence' event.
              </p>
              <small class="text-light-grey fw-semibold">2 HOURS AGO</small>
            </li>

            <li class="timeline-item">
              <span class="timeline-icon d-flex justify-content-center align-items-center timeline-approved">
                <i class="bi bi-check-lg"></i>
              </span>
              <p class="fw-bold mb-0 fs-6">Event Published</p>
              <p class="text-secondary fs-09 mb-0">
                Quarterly awards event has been published successfully.
              </p>
              <small class="text-light-grey fw-semibold">YESTERDAY</small>
            </li>
          </ul>
        </section>
      </div>
    </div>
  </div>
</div>

<!-- Assigned Events Table Area -->
<div class="border mt-4 rounded-3 p-2">
  <div class="d-flex justify-content-between">
    <p class="fw-bold lh-1 mb-2">Assigned Events</p>
    <p class="mb-0 fs-09">5 Rows</p>
  </div>
  <div class="table-responsive">
    <table class="table table-borderless">
      <thead>
        <tr class="table-light">
          <th scope="col" class="fs-09 fw-semibold" style="min-width: 70px">Serial No.</th>
          <th scope="col" class="fs-09 fw-semibold" style="min-width: 150px">Event Name</th>
          <th scope="col" class="fs-09 fw-semibold" style="min-width: 120px">Start Date</th>
          <th scope="col" class="fs-09 fw-semibold" style="min-width: 100px">Nominations</th>
          <th scope="col" class="fs-09 fw-semibold" style="min-width: 100px">Action</th>
        </tr>
      </thead>
      <tbody>
        @for ($i = 1; $i <= 5; $i++)
        <tr class="{{ $i % 2 == 0 ? 'table-light' : '' }}">
          <td>{{ $i }}</td>
          <td>
            <p class="lh-1">Q3 Performance Awards</p>
            <small class="text-light-grey lh-1">Cultural Event</small>
          </td>
          <td>Dec 1, 2024</td>
          <td>
            <button class="btn btn-primary btn-sm bg-primary">Apply</button>
          </td>
          <td>
            <button class="btn btn-sm btn-link text-decoration-none">View</button>
          </td>
        </tr>
        @endfor
      </tbody>
    </table>
  </div>
</div>
@endsection