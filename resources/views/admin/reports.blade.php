@extends('layouts.app')

@section('content')
<!-- SECTION H: REPORTS ENGINE WORKSPACE -->
<div id="panel-reports" class="admin-panel bg-white p-4 rounded-4 border">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h2>Reports Engine Workspace</h2>
      <p class="text-secondary fs-09">Expose operational dashboard queries and export spreadsheets.</p>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-success btn-sm bg-success text-white" onclick="alert('Exporting Report to CSV...')"><i class="bi bi-file-earmark-spreadsheet me-1"></i> Export to CSV</button>
      <button class="btn btn-primary btn-sm bg-primary" onclick="alert('Exporting Report to Excel...')"><i class="bi bi-file-earmark-excel me-1"></i> Export to Excel</button>
    </div>
  </div>

  <div class="row g-4">
    <!-- Query Table 1: Total nominators nominated for single event -->
    <div class="col-md-4">
      <div class="border rounded-4 p-3 bg-white h-100">
        <h6 class="fw-bold mb-3 small">Nominator Nominated / Event</h6>
        <div class="table-responsive">
          <table class="table table-striped table-sm align-middle">
            <thead>
              <tr class="table-light">
                <th>Event Name</th>
                <th class="text-end">Count</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Tech Summit 2026</td>
                <td class="text-end">18</td>
              </tr>
              <tr>
                <td>Q3 Performance</td>
                <td class="text-end">28</td>
              </tr>
              <tr>
                <td>Leadership Offsite</td>
                <td class="text-end">12</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Query Table 2: Total number of events created -->
    <div class="col-md-4">
      <div class="border rounded-4 p-3 bg-white h-100">
        <h6 class="fw-bold mb-3 small">Events Configured</h6>
        <div class="table-responsive">
          <table class="table table-striped table-sm align-middle">
            <thead>
              <tr class="table-light">
                <th>Type</th>
                <th class="text-end">Total</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Hospitality</td>
                <td class="text-end">8</td>
              </tr>
              <tr>
                <td>Non-Hospitality</td>
                <td class="text-end">12</td>
              </tr>
              <tr class="table-info">
                <td>Total</td>
                <td class="text-end">20</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Query Table 3: Total number of nominators contact records -->
    <div class="col-md-4">
      <div class="border rounded-4 p-3 bg-white h-100">
        <h6 class="fw-bold mb-3 small">Nominator Contacts Database</h6>
        <div class="table-responsive">
          <table class="table table-striped table-sm align-middle">
            <thead>
              <tr class="table-light">
                <th>Tier</th>
                <th class="text-end">Contacts</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Active</td>
                <td class="text-end">450</td>
              </tr>
              <tr>
                <td>Unsubscribed</td>
                <td class="text-end">24</td>
              </tr>
              <tr>
                <td>Exclusion Blocked</td>
                <td class="text-end">12</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
