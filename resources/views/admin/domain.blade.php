@extends('layouts.app')

@section('content')
<!-- SECTION I: ENTERPRISE DOMAIN MANAGEMENT PANEL -->
<div id="panel-domain" class="admin-panel bg-white p-4 rounded-4 border">
  <h2>Enterprise Domain Management Panel</h2>
  <p class="text-secondary fs-09 mb-4">Set access controls and calculate enrollment capacities across accounts.</p>

  <div class="row g-4">
    <!-- Domain Setup Form -->
    <div class="col-lg-6">
      <div class="border rounded-4 p-4">
        <h5 class="fw-bold mb-3 small">Configure Account Access</h5>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-bold small">Enterprise Boundary Name</label>
            <select class="form-select form-select-sm" id="domain-enterprise-name" onchange="updateCalculatorLimits()">
              <option value="Infosys">Infosys</option>
              <option value="NeoSoft">NeoSoft</option>
              <option value="Adobe">Adobe</option>
              <option value="Cisco">Cisco</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold small">Domain Access Level</label>
            <select class="form-select form-select-sm" id="domain-access-level">
              <option value="Full Access">Full Access</option>
              <option value="Restricted">Restricted</option>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label fw-bold small">Set Domain Max Enrollment Cap</label>
            <input type="number" class="form-control form-control-sm" id="domain-capping-limit" value="10" oninput="updateCalculatorLimits()" />
          </div>
        </div>
      </div>
    </div>

    <!-- Account Boundary Calculator UI -->
    <div class="col-lg-6">
      <div class="border rounded-4 p-4 bg-light">
        <h5 class="fw-bold mb-3 small">Account Boundary Capacity Calculator</h5>
        
        <div class="row g-3">
          <div class="col-6">
            <small class="text-secondary d-block">Current Active State count (Sent/Accepted):</small>
            <span class="fs-4 fw-bold text-primary" id="calc-active-state">4</span>
          </div>
          <div class="col-6">
            <small class="text-secondary d-block">Max Domain Capping Limit:</small>
            <span class="fs-4 fw-bold text-dark" id="calc-cap-limit">10</span>
          </div>
          
          <div class="col-12">
            <label class="form-label fw-bold small">Simulate Nomination Entries Upload count</label>
            <input type="number" class="form-control form-control-sm" id="calc-upload-count" value="5" />
          </div>
          
          <div class="col-12">
            <button class="btn btn-sm btn-primary bg-primary px-3 w-100" onclick="calculateAccountBoundaryLimit()">Validate Nomination Submission</button>
          </div>
        </div>

        <!-- Validation failure notification banner -->
        <div class="alert alert-danger mt-3 d-none" id="calc-alert-banner">
          <i class="bi bi-x-octagon-fill me-2 fs-5"></i>
          <span id="calc-alert-msg">Validation Alert</span>
        </div>
        
        <!-- Validation success banner -->
        <div class="alert alert-success mt-3 d-none" id="calc-success-banner">
          <i class="bi bi-check-circle-fill me-2 fs-5"></i>
          <span>Validation success. Allocation falls within domain account boundary constraints.</span>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
const domainLimits = {
    'Infosys': 50,
    'NeoSoft': 30,
    'Adobe': 15,
    'Cisco': 10
};
const domainActives = {
    'Infosys': 24,
    'NeoSoft': 12,
    'Adobe': 8,
    'Cisco': 4
};

function updateCalculatorLimits() {
    const selectedEnt = document.getElementById('domain-enterprise-name').value;
    const currentLimitInput = document.getElementById('domain-capping-limit');
    
    // Check if input was manually changed by user, otherwise set standard defaults
    if (document.activeElement !== currentLimitInput) {
        currentLimitInput.value = domainLimits[selectedEnt];
    }
    
    domainLimits[selectedEnt] = parseInt(currentLimitInput.value);

    // Update calculator indicators
    document.getElementById('calc-active-state').textContent = domainActives[selectedEnt];
    document.getElementById('calc-cap-limit').textContent = domainLimits[selectedEnt];
}

function calculateAccountBoundaryLimit() {
    const selectedEnt = document.getElementById('domain-enterprise-name').value;
    const activeStateCount = domainActives[selectedEnt];
    const maxCapLimit = domainLimits[selectedEnt];
    const uploadRequestCount = parseInt(document.getElementById('calc-upload-count').value);

    const alertBanner = document.getElementById('calc-alert-banner');
    const successBanner = document.getElementById('calc-success-banner');

    const totalProposed = activeStateCount + uploadRequestCount;
    const vacantSlots = maxCapLimit - activeStateCount;

    if (totalProposed > maxCapLimit) {
        // Halt submission processing and trigger validation warning alert
        successBanner.classList.add('d-none');
        alertBanner.classList.remove('d-none');
        document.getElementById('calc-alert-msg').textContent = `Capped: Only [${vacantSlots >= 0 ? vacantSlots : 0}] vacant nomination slots remain for this account domain.`;
    } else {
        alertBanner.classList.add('d-none');
        successBanner.classList.remove('d-none');
        showToast("Access Allocation Validated!");
    }
}

// Init calculator fields on load
document.addEventListener('DOMContentLoaded', () => {
    updateCalculatorLimits();
});
</script>
@endpush
