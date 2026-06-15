@extends('layouts.app')

@section('content')
<!-- SECTION E: EXCLUSION LIST -->
<div id="panel-exclusion" class="admin-panel bg-white p-4 rounded-4 border">
  <div class="border rounded-4 p-4 bg-light">
    <h4 class="fw-bold mb-3">Exclusion List Hub</h4>
    
    <!-- Warning alert banner explicitly required -->
    <div class="alert alert-danger d-flex align-items-start shadow-sm mb-3">
      <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
      <div>
        <strong class="d-block">Names added to the exclusion list can never be nominated for events.</strong>
      </div>
    </div>

    <div class="border border-dashed rounded-4 p-4 text-center bg-white mb-3" style="border: 2px dashed #ccc;">
      <i class="bi bi-shield-x fs-1 text-danger"></i>
      <p class="small text-muted mt-2">Drag and drop unsubscribed emails CSV file here</p>
      <input type="file" id="exclusion-csv-input" class="form-control form-control-sm" onchange="simulateExclusionUpload(event)" />
    </div>

    <h6 class="fw-bold text-dark mt-4">Simulate Exclusion Check</h6>
    <div class="input-group input-group-sm mb-3">
      <input type="email" class="form-control" id="exclusion-test-email" placeholder="test@email.com" />
      <button class="btn btn-danger text-white" type="button" onclick="testExclusionEmail()">Check Blocklist</button>
    </div>
    <div class="alert alert-danger small fw-semibold d-none" id="exclusion-alert-box"></div>
  </div>
</div>
@endsection

@push('scripts')
<script>
const exclusionEmails = ['blocked@example.com', 'spam@domain.com', 'blacklist@infosys.com'];

function simulateExclusionUpload(e) {
    const file = e.target.files[0];
    if (file) {
        exclusionEmails.push('uploaded-banned@email.com');
        showToast("Exclusion blocklist updated with uploaded CSV emails.");
    }
}

function testExclusionEmail() {
    const email = document.getElementById('exclusion-test-email').value.trim();
    const alertBox = document.getElementById('exclusion-alert-box');
    
    if (!email) return;

    if (exclusionEmails.includes(email)) {
        alertBox.textContent = "Validation Error: This email is on the exclusion list and cannot be nominated.";
        alertBox.classList.remove('d-none');
    } else {
        alertBox.classList.add('d-none');
        showToast("Email validation clean. Nominator eligible for events.");
    }
}
</script>
@endpush
