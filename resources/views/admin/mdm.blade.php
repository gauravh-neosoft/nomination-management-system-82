@extends('layouts.app')

@section('content')
<!-- SECTION F: MASTER DATA MANAGEMENT (MDM HUB) -->
<div id="panel-mdm" class="admin-panel bg-white p-4 rounded-4 border">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h2 class="fw-bold text-dark">Master Data Management (MDM Hub)</h2>
      <p class="text-secondary fs-09">Bulk ingest records and review status logs.</p>
    </div>
  </div>

  <!-- Bulk Ingestion Content -->
  <div class="border rounded-4 p-4 bg-light shadow-sm">
    <div class="row g-4">
      <div class="col-md-5">
        <div class="border border-dashed rounded-4 p-4 text-center bg-white" style="border: 2px dashed #ccc; height: 100%;">
          <i class="bi bi-cloud-arrow-up fs-1 text-primary"></i>
          <h6 class="fw-bold mt-2">Upload Master Sheet</h6>
          <p class="text-muted small">Ingest CSV or Excel datasets for automated deduplication parsing.</p>
          <input type="file" class="form-control form-control-sm" onchange="simulateMDMUpload(event)" />
        </div>
      </div>
      
      <div class="col-md-7">
        <div class="border rounded-4 p-3 bg-white" style="min-height: 200px;">
          <h6 class="fw-bold mb-3 small">Ingestion Status Logs</h6>
          <div class="table-responsive">
            <table class="table table-sm align-middle" id="mdm-log-table">
              <thead>
                <tr class="table-light">
                  <th>Record Ingested</th>
                  <th>Validation Verification Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>gaurav.heda@company.com</td>
                  <td><span class="badge bg-warning text-dark">Duplicate - Ignored</span></td>
                </tr>
                <tr>
                  <td>tony.stark@starkindustries.com</td>
                  <td><span class="badge bg-success">Unique - Saved</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function simulateMDMUpload(e) {
    const file = e.target.files[0];
    if (file) {
        const tableBody = document.querySelector('#mdm-log-table tbody');
        
        const tr1 = document.createElement('tr');
        tr1.innerHTML = `
            <td>bruce@waynecorp.com</td>
            <td><span class="badge bg-warning text-dark">Duplicate - Ignored</span></td>
        `;
        const tr2 = document.createElement('tr');
        tr2.innerHTML = `
            <td>arthur.dent@galaxy.org</td>
            <td><span class="badge bg-success">Unique - Saved</span></td>
        `;

        tableBody.prepend(tr2);
        tableBody.prepend(tr1);
        showToast("Records ingested. Duplication filter processing complete.");
    }
}
</script>
@endpush
