@extends('layouts.app')

@section('content')
<!-- SECTION: DROPDOWN MANAGEMENT -->
<div id="panel-dropdown" class="admin-panel bg-white p-4 rounded-4 border">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h2 class="fw-bold text-dark">Dropdown Management</h2>
      <p class="text-secondary fs-09">Configure Business Units, Sub Units, and GDPR Compliance policies.</p>
    </div>
  </div>

  <!-- Duplicate prevention alert -->
  <div class="alert alert-warning d-flex align-items-center shadow-sm mb-4 border-0 bg-light-orange bg-opacity-25 rounded-3">
    <i class="bi bi-shield-exclamation me-3 fs-3 text-warning"></i>
    <div>
      <p class="mb-0 fw-semibold text-dark">Data integrity constraint checks are active.</p>
      <small class="text-secondary">System checks inputs for duplicates. Deleting a Business Unit will cascade delete all its linked Sub Units.</small>
    </div>
  </div>

  <!-- Tabs Navigation -->
  <ul class="nav nav-pills mb-4 gap-2 bg-light p-2 rounded-3 border" id="dropdownTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active rounded-2 px-4 py-2" id="units-tab" data-bs-toggle="pill" data-bs-target="#units-pane" type="button" role="tab" aria-controls="units-pane" aria-selected="true">
        <i class="bi bi-building me-2"></i>Business Units
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link rounded-2 px-4 py-2" id="subunits-tab" data-bs-toggle="pill" data-bs-target="#subunits-pane" type="button" role="tab" aria-controls="subunits-pane" aria-selected="false">
        <i class="bi bi-diagram-3 me-2"></i>Sub Units
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link rounded-2 px-4 py-2" id="gdpr-tab" data-bs-toggle="pill" data-bs-target="#gdpr-pane" type="button" role="tab" aria-controls="gdpr-pane" aria-selected="false">
        <i class="bi bi-shield-check me-2"></i>GDPR Compliance
      </button>
    </li>
  </ul>

  <!-- Tab Contents -->
  <div class="tab-content" id="dropdownTabsContent">
    
    <!-- PANE 1: BUSINESS UNITS -->
    <div class="tab-pane fade show active animate-fade-in" id="units-pane" role="tabpanel" aria-labelledby="units-tab">
      
      <!-- Stats Cards -->
      <div class="row g-3 mb-4">
        <div class="col-md-3">
          <div class="border rounded-4 p-3 bg-light text-start shadow-sm border-light-grey">
            <span class="text-secondary uppercase fs-07 fw-bold d-block">Total Business Units</span>
            <h3 class="fw-bold mt-1 mb-0 text-primary" id="stat-total-units">0</h3>
          </div>
        </div>
        <div class="col-md-3">
          <div class="border rounded-4 p-3 bg-light text-start shadow-sm border-light-grey">
            <span class="text-secondary uppercase fs-07 fw-bold d-block">Active Business Units</span>
            <h3 class="fw-bold mt-1 mb-0 text-success" id="stat-active-units">0</h3>
          </div>
        </div>
      </div>

      <!-- Action Bar -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="position-relative w-50 max-width-300">
          <input type="text" id="search-units" class="form-control form-control-sm ps-4 rounded-3" placeholder="Search units...">
          <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-2 text-secondary fs-09"></i>
        </div>
        <button class="btn btn-primary btn-sm rounded-3 px-3 bg-primary" onclick="openAddUnitModal()">
          <i class="bi bi-plus-lg me-1"></i>Add Unit
        </button>
      </div>

      <!-- Table -->
      <div class="table-responsive border rounded-3 bg-white">
        <table class="table table-hover align-middle mb-0" id="units-table">
          <thead>
            <tr class="table-light">
              <th class="text-start ps-4" style="width: 100px">ID</th>
              <th class="text-start">Unit Name</th>
              <th style="width: 150px">Status</th>
              <th style="width: 150px">Toggle Active</th>
              <th style="width: 150px">Actions</th>
            </tr>
          </thead>
          <tbody>
            <!-- Dynamic Rows -->
          </tbody>
        </table>
      </div>
    </div>

    <!-- PANE 2: SUB UNITS -->
    <div class="tab-pane fade animate-fade-in" id="subunits-pane" role="tabpanel" aria-labelledby="subunits-tab">
      
      <!-- Stats Cards -->
      <div class="row g-3 mb-4">
        <div class="col-md-3">
          <div class="border rounded-4 p-3 bg-light text-start shadow-sm border-light-grey">
            <span class="text-secondary uppercase fs-07 fw-bold d-block">Total Sub Units</span>
            <h3 class="fw-bold mt-1 mb-0 text-primary" id="stat-total-subunits">0</h3>
          </div>
        </div>
        <div class="col-md-3">
          <div class="border rounded-4 p-3 bg-light text-start shadow-sm border-light-grey">
            <span class="text-secondary uppercase fs-07 fw-bold d-block">Active Sub Units</span>
            <h3 class="fw-bold mt-1 mb-0 text-success" id="stat-active-subunits">0</h3>
          </div>
        </div>
      </div>

      <!-- Action & Filter Bar -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-3 w-75 align-items-center">
          <div class="position-relative w-50 max-width-300">
            <input type="text" id="search-subunits" class="form-control form-control-sm ps-4 rounded-3" placeholder="Search sub-units...">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-2 text-secondary fs-09"></i>
          </div>
          <div style="min-width: 200px">
            <select id="filter-parent-unit" class="form-select form-select-sm rounded-3" onchange="filterSubUnitsByParent(this.value)">
              <option value="">All Business Units</option>
              <!-- Dynamic Options -->
            </select>
          </div>
        </div>
        <button class="btn btn-primary btn-sm rounded-3 px-3 bg-primary" onclick="openAddSubUnitModal()">
          <i class="bi bi-plus-lg me-1"></i>Add Sub Unit
        </button>
      </div>

      <!-- Table -->
      <div class="table-responsive border rounded-3 bg-white">
        <table class="table table-hover align-middle mb-0" id="subunits-table">
          <thead>
            <tr class="table-light">
              <th class="text-start ps-4" style="width: 100px">ID</th>
              <th class="text-start">Parent Unit</th>
              <th class="text-start">Sub Unit Name</th>
              <th style="width: 150px">Status</th>
              <th style="width: 150px">Toggle Active</th>
              <th style="width: 150px">Actions</th>
            </tr>
          </thead>
          <tbody>
            <!-- Dynamic Rows -->
          </tbody>
        </table>
      </div>
    </div>

    <!-- PANE 3: GDPR COMPLIANCE -->
    <div class="tab-pane fade animate-fade-in" id="gdpr-pane" role="tabpanel" aria-labelledby="gdpr-tab">
      
      <!-- Stats Cards -->
      <div class="row g-3 mb-4">
        <div class="col-md-3">
          <div class="border rounded-4 p-3 bg-light text-start shadow-sm border-light-grey">
            <span class="text-secondary uppercase fs-07 fw-bold d-block">Total Policies</span>
            <h3 class="fw-bold mt-1 mb-0 text-primary" id="stat-total-gdpr">0</h3>
          </div>
        </div>
        <div class="col-md-3">
          <div class="border rounded-4 p-3 bg-light text-start shadow-sm border-light-grey">
            <span class="text-secondary uppercase fs-07 fw-bold d-block">Active Policies</span>
            <h3 class="fw-bold mt-1 mb-0 text-success" id="stat-active-gdpr">0</h3>
          </div>
        </div>
      </div>

      <!-- Action Bar -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="position-relative w-50 max-width-300">
          <input type="text" id="search-gdpr" class="form-control form-control-sm ps-4 rounded-3" placeholder="Search consent policies...">
          <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-2 text-secondary fs-09"></i>
        </div>
        <button class="btn btn-primary btn-sm rounded-3 px-3 bg-primary" onclick="openAddGdprModal()">
          <i class="bi bi-plus-lg me-1"></i>Add Consent Option
        </button>
      </div>

      <!-- Table -->
      <div class="table-responsive border rounded-3 bg-white">
        <table class="table table-hover align-middle mb-0" id="gdpr-table">
          <thead>
            <tr class="table-light">
              <th class="text-start ps-4" style="width: 100px">ID</th>
              <th class="text-start">Consent Text</th>
              <th style="width: 150px">Status</th>
              <th style="width: 150px">Toggle Active</th>
              <th style="width: 150px">Actions</th>
            </tr>
          </thead>
          <tbody>
            <!-- Dynamic Rows -->
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

<!-- Add Unit Modal -->
<div class="modal fade" id="addUnitModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header border-0 bg-light-orange bg-opacity-10 py-3">
        <h5 class="modal-title fw-bold">Add Business Unit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4">
        <form id="add-unit-form">
          <div class="mb-3">
            <label class="form-label fw-bold">Unit Name</label>
            <input type="text" name="name" class="form-control rounded-3" placeholder="e.g. Health & Safety" required>
            <div class="invalid-feedback" id="add-unit-error-name"></div>
          </div>
          <div class="text-end">
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 me-2" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm btn-primary bg-primary rounded-3">Save Unit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Unit Modal -->
<div class="modal fade" id="editUnitModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header border-0 bg-light-orange bg-opacity-10 py-3">
        <h5 class="modal-title fw-bold">Edit Business Unit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4">
        <form id="edit-unit-form">
          <input type="hidden" name="id" id="edit-unit-id">
          <div class="mb-3">
            <label class="form-label fw-bold">Unit Name</label>
            <input type="text" name="name" id="edit-unit-name" class="form-control rounded-3" required>
            <div class="invalid-feedback" id="edit-unit-error-name"></div>
          </div>
          <div class="text-end">
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 me-2" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm btn-primary bg-primary rounded-3">Update Unit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Add Sub Unit Modal -->
<div class="modal fade" id="addSubUnitModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header border-0 bg-light-orange bg-opacity-10 py-3">
        <h5 class="modal-title fw-bold">Add Sub Unit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4">
        <form id="add-subunit-form">
          <div class="mb-3">
            <label class="form-label fw-bold">Parent Business Unit</label>
            <select name="unit_id" id="add-subunit-parent-select" class="form-select rounded-3" required>
              <option value="">Select Business Unit...</option>
              <!-- Dynamic Options -->
            </select>
            <div class="invalid-feedback" id="add-subunit-error-unit_id"></div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Sub Unit Name</label>
            <input type="text" name="name" class="form-control rounded-3" placeholder="e.g. Life Insurance" required>
            <div class="invalid-feedback" id="add-subunit-error-name"></div>
          </div>
          <div class="text-end">
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 me-2" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm btn-primary bg-primary rounded-3">Save Sub Unit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Sub Unit Modal -->
<div class="modal fade" id="editSubUnitModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header border-0 bg-light-orange bg-opacity-10 py-3">
        <h5 class="modal-title fw-bold">Edit Sub Unit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4">
        <form id="edit-subunit-form">
          <input type="hidden" name="id" id="edit-subunit-id">
          <div class="mb-3">
            <label class="form-label fw-bold">Parent Business Unit</label>
            <select name="unit_id" id="edit-subunit-parent-select" class="form-select rounded-3" required>
              <!-- Dynamic Options -->
            </select>
            <div class="invalid-feedback" id="edit-subunit-error-unit_id"></div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Sub Unit Name</label>
            <input type="text" name="name" id="edit-subunit-name" class="form-control rounded-3" required>
            <div class="invalid-feedback" id="edit-subunit-error-name"></div>
          </div>
          <div class="text-end">
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 me-2" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm btn-primary bg-primary rounded-3">Update Sub Unit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Add GDPR Modal -->
<div class="modal fade" id="addGdprModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header border-0 bg-light-orange bg-opacity-10 py-3">
        <h5 class="modal-title fw-bold">Add GDPR Consent Option</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4">
        <form id="add-gdpr-form">
          <div class="mb-3">
            <label class="form-label fw-bold">Consent Option Text</label>
            <textarea name="name" class="form-control rounded-3" rows="3" placeholder="e.g. Existing Business Relationship (Client)" required></textarea>
            <div class="invalid-feedback" id="add-gdpr-error-name"></div>
          </div>
          <div class="text-end">
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 me-2" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm btn-primary bg-primary rounded-3">Save Option</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit GDPR Modal -->
<div class="modal fade" id="editGdprModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header border-0 bg-light-orange bg-opacity-10 py-3">
        <h5 class="modal-title fw-bold">Edit GDPR Consent Option</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4">
        <form id="edit-gdpr-form">
          <input type="hidden" name="id" id="edit-gdpr-id">
          <div class="mb-3">
            <label class="form-label fw-bold">Consent Option Text</label>
            <textarea name="name" id="edit-gdpr-name" class="form-control rounded-3" rows="3" required></textarea>
            <div class="invalid-feedback" id="edit-gdpr-error-name"></div>
          </div>
          <div class="text-end">
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 me-2" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm btn-primary bg-primary rounded-3">Update Option</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// Global Instances of Bootstrap Modals
const modals = {
    addUnit: new bootstrap.Modal(document.getElementById('addUnitModal')),
    editUnit: new bootstrap.Modal(document.getElementById('editUnitModal')),
    addSubUnit: new bootstrap.Modal(document.getElementById('addSubUnitModal')),
    editSubUnit: new bootstrap.Modal(document.getElementById('editSubUnitModal')),
    addGdpr: new bootstrap.Modal(document.getElementById('addGdprModal')),
    editGdpr: new bootstrap.Modal(document.getElementById('editGdprModal'))
};

// Common Headers for AJAX
const requestHeaders = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-CSRF-TOKEN': '{{ csrf_token() }}'
};

// Utility Escape Handlers
function escapeHtml(string) {
    return String(string).replace(/[&<>"']/g, function (s) {
        return {
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            '"': '&quot;',
            "'": '&#39;'
        }[s];
    });
}

// -------------------------------------------------------------
// A. Business Units Handler
// -------------------------------------------------------------
function renderUnits() {
    fetch("{{ route('admin-dropdown-get-units') }}")
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector('#units-table tbody');
            tbody.innerHTML = '';
            
            let total = data.length;
            let active = 0;

            // Update Add Sub Unit Parent Selection & Filters as well
            const parentSelects = [
                document.getElementById('add-subunit-parent-select'),
                document.getElementById('edit-subunit-parent-select')
            ];
            
            const filterSelect = document.getElementById('filter-parent-unit');
            const savedFilterValue = filterSelect.value;
            
            parentSelects.forEach(sel => {
                sel.innerHTML = '<option value="">Select Business Unit...</option>';
            });
            filterSelect.innerHTML = '<option value="">All Business Units</option>';

            data.forEach(unit => {
                if (unit.is_active) {
                    active++;
                    // Populate selects with active units
                    parentSelects.forEach(sel => {
                        sel.innerHTML += `<option value="${unit.id}">${escapeHtml(unit.name)}</option>`;
                    });
                }
                
                filterSelect.innerHTML += `<option value="${unit.id}">${escapeHtml(unit.name)}</option>`;

                const tr = document.createElement('tr');
                tr.id = `unit-row-${unit.id}`;
                tr.innerHTML = `
                    <td class="text-start ps-4 fw-semibold">${unit.id}</td>
                    <td class="text-start unit-name">${escapeHtml(unit.name)}</td>
                    <td>
                        <span class="badge ${unit.is_active ? 'bg-success' : 'bg-danger'}">
                            ${unit.is_active ? 'Active' : 'Inactive'}
                        </span>
                    </td>
                    <td>
                        <div class="form-check form-switch d-inline-block">
                            <input class="form-check-input" type="checkbox" ${unit.is_active ? 'checked' : ''} onchange="toggleUnit(${unit.id}, this)">
                        </div>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary me-1 py-0 px-2" onclick="openEditUnitModal(${unit.id}, '${escapeHtml(unit.name)}')">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger py-0 px-2" onclick="deleteUnit(${unit.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            // Restore filter value if any
            filterSelect.value = savedFilterValue;

            document.getElementById('stat-total-units').textContent = total;
            document.getElementById('stat-active-units').textContent = active;
        });
}

function openAddUnitModal() {
    clearValidationErrors('add-unit-form');
    document.getElementById('add-unit-form').reset();
    modals.addUnit.show();
}

document.getElementById('add-unit-form').addEventListener('submit', function(e) {
    e.preventDefault();
    clearValidationErrors('add-unit-form');
    const formData = new FormData(this);
    
    fetch("{{ route('admin-dropdown-store-unit') }}", {
        method: 'POST',
        headers: requestHeaders,
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(async res => {
        const data = await res.json();
        if (res.ok) {
            modals.addUnit.hide();
            showToast(data.message);
            renderUnits();
        } else {
            handleValidationErrors('add-unit-form', data.errors);
        }
    })
    .catch(() => showToast("Failed to save Business Unit.", 'bg-danger'));
});

function openEditUnitModal(id, name) {
    clearValidationErrors('edit-unit-form');
    document.getElementById('edit-unit-id').value = id;
    document.getElementById('edit-unit-name').value = name;
    modals.editUnit.show();
}

document.getElementById('edit-unit-form').addEventListener('submit', function(e) {
    e.preventDefault();
    clearValidationErrors('edit-unit-form');
    const id = document.getElementById('edit-unit-id').value;
    const formData = new FormData(this);
    
    fetch(`/admin/dropdown/units/${id}/update`, {
        method: 'POST',
        headers: requestHeaders,
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(async res => {
        const data = await res.json();
        if (res.ok) {
            modals.editUnit.hide();
            showToast(data.message);
            renderUnits();
            renderSubUnits(); // Since parent unit name could have changed
        } else {
            handleValidationErrors('edit-unit-form', data.errors);
        }
    })
    .catch(() => showToast("Failed to update Business Unit.", 'bg-danger'));
});

function toggleUnit(id, checkbox) {
    fetch(`/admin/dropdown/units/${id}/toggle`, {
        method: 'POST',
        headers: requestHeaders
    })
    .then(res => res.json())
    .then(data => {
        showToast(data.message);
        renderUnits();
        renderSubUnits(); // Subunits list might need status updating/reloading
    })
    .catch(() => {
        checkbox.checked = !checkbox.checked;
        showToast("Failed to update status.", 'bg-danger');
    });
}

function deleteUnit(id) {
    if (confirm("Are you sure you want to delete this Business Unit? Doing so will permanently delete all its linked Sub Units!")) {
        fetch(`/admin/dropdown/units/${id}/delete`, {
            method: 'POST',
            headers: requestHeaders
        })
        .then(res => res.json())
        .then(data => {
            showToast(data.message);
            renderUnits();
            renderSubUnits();
        })
        .catch(() => showToast("Failed to delete unit.", 'bg-danger'));
    }
}

// -------------------------------------------------------------
// B. Sub Units Handler
// -------------------------------------------------------------
let allSubUnitsData = [];

function renderSubUnits() {
    fetch("{{ route('admin-dropdown-get-subunits') }}")
        .then(res => res.json())
        .then(data => {
            allSubUnitsData = data;
            filterSubUnits();
        });
}

function filterSubUnits() {
    const parentFilter = document.getElementById('filter-parent-unit').value;
    const searchVal = document.getElementById('search-subunits').value.toLowerCase();
    const tbody = document.querySelector('#subunits-table tbody');
    tbody.innerHTML = '';
    
    let total = allSubUnitsData.length;
    let active = 0;

    allSubUnitsData.forEach(sub => {
        if (sub.is_active && sub.unit?.is_active) {
            active++;
        }

        // Apply filters
        if (parentFilter && sub.unit_id != parentFilter) return;
        if (searchVal && !sub.name.toLowerCase().includes(searchVal) && !sub.unit?.name.toLowerCase().includes(searchVal)) return;

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="text-start ps-4 fw-semibold">${sub.id}</td>
            <td class="text-start text-muted">${escapeHtml(sub.unit ? sub.unit.name : 'N/A')} ${sub.unit && !sub.unit.is_active ? '<span class="badge bg-danger fs-06">Unit Inactive</span>' : ''}</td>
            <td class="text-start subunit-name fw-semibold">${escapeHtml(sub.name)}</td>
            <td>
                <span class="badge ${sub.is_active && (sub.unit ? sub.unit.is_active : true) ? 'bg-success' : 'bg-danger'}">
                    ${sub.is_active && (sub.unit ? sub.unit.is_active : true) ? 'Active' : 'Inactive'}
                </span>
            </td>
            <td>
                <div class="form-check form-switch d-inline-block">
                    <input class="form-check-input" type="checkbox" ${sub.is_active ? 'checked' : ''} onchange="toggleSubUnit(${sub.id}, this)">
                </div>
            </td>
            <td>
                <button class="btn btn-sm btn-outline-primary me-1 py-0 px-2" onclick="openEditSubUnitModal(${sub.id}, ${sub.unit_id}, '${escapeHtml(sub.name)}')">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger py-0 px-2" onclick="deleteSubUnit(${sub.id})">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });

    document.getElementById('stat-total-subunits').textContent = total;
    document.getElementById('stat-active-subunits').textContent = active;
}

function filterSubUnitsByParent(unitId) {
    filterSubUnits();
}

function openAddSubUnitModal() {
    clearValidationErrors('add-subunit-form');
    document.getElementById('add-subunit-form').reset();
    modals.addSubUnit.show();
}

document.getElementById('add-subunit-form').addEventListener('submit', function(e) {
    e.preventDefault();
    clearValidationErrors('add-subunit-form');
    const formData = new FormData(this);
    
    fetch("{{ route('admin-dropdown-store-subunit') }}", {
        method: 'POST',
        headers: requestHeaders,
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(async res => {
        const data = await res.json();
        if (res.ok) {
            modals.addSubUnit.hide();
            showToast(data.message);
            renderSubUnits();
        } else {
            handleValidationErrors('add-subunit-form', data.errors);
        }
    })
    .catch(() => showToast("Failed to save Sub Unit.", 'bg-danger'));
});

function openEditSubUnitModal(id, unitId, name) {
    clearValidationErrors('edit-subunit-form');
    document.getElementById('edit-subunit-id').value = id;
    document.getElementById('edit-subunit-parent-select').value = unitId;
    document.getElementById('edit-subunit-name').value = name;
    modals.editSubUnit.show();
}

document.getElementById('edit-subunit-form').addEventListener('submit', function(e) {
    e.preventDefault();
    clearValidationErrors('edit-subunit-form');
    const id = document.getElementById('edit-subunit-id').value;
    const formData = new FormData(this);
    
    fetch(`/admin/dropdown/sub-units/${id}/update`, {
        method: 'POST',
        headers: requestHeaders,
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(async res => {
        const data = await res.json();
        if (res.ok) {
            modals.editSubUnit.hide();
            showToast(data.message);
            renderSubUnits();
        } else {
            handleValidationErrors('edit-subunit-form', data.errors);
        }
    })
    .catch(() => showToast("Failed to update Sub Unit.", 'bg-danger'));
});

function toggleSubUnit(id, checkbox) {
    fetch(`/admin/dropdown/sub-units/${id}/toggle`, {
        method: 'POST',
        headers: requestHeaders
    })
    .then(res => res.json())
    .then(data => {
        showToast(data.message);
        renderSubUnits();
    })
    .catch(() => {
        checkbox.checked = !checkbox.checked;
        showToast("Failed to update status.", 'bg-danger');
    });
}

// -------------------------------------------------------------
// C. GDPR Compliance Handler
// -------------------------------------------------------------
function renderGdpr() {
    fetch("{{ route('admin-dropdown-get-gdpr') }}")
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector('#gdpr-table tbody');
            tbody.innerHTML = '';
            
            let total = data.length;
            let active = 0;

            data.forEach(item => {
                if (item.is_active) active++;

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="text-start ps-4 fw-semibold">${item.id}</td>
                    <td class="text-start gdpr-name small fw-semibold text-wrap">${escapeHtml(item.name)}</td>
                    <td>
                        <span class="badge ${item.is_active ? 'bg-success' : 'bg-danger'}">
                            ${item.is_active ? 'Active' : 'Inactive'}
                        </span>
                    </td>
                    <td>
                        <div class="form-check form-switch d-inline-block">
                            <input class="form-check-input" type="checkbox" ${item.is_active ? 'checked' : ''} onchange="toggleGdpr(${item.id}, this)">
                        </div>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary me-1 py-0 px-2" onclick="openEditGdprModal(${item.id}, '${escapeHtml(item.name)}')">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger py-0 px-2" onclick="deleteGdpr(${item.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            document.getElementById('stat-total-gdpr').textContent = total;
            document.getElementById('stat-active-gdpr').textContent = active;
        });
}

function openAddGdprModal() {
    clearValidationErrors('add-gdpr-form');
    document.getElementById('add-gdpr-form').reset();
    modals.addGdpr.show();
}

document.getElementById('add-gdpr-form').addEventListener('submit', function(e) {
    e.preventDefault();
    clearValidationErrors('add-gdpr-form');
    const formData = new FormData(this);
    
    fetch("{{ route('admin-dropdown-store-gdpr') }}", {
        method: 'POST',
        headers: requestHeaders,
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(async res => {
        const data = await res.json();
        if (res.ok) {
            modals.addGdpr.hide();
            showToast(data.message);
            renderGdpr();
        } else {
            handleValidationErrors('add-gdpr-form', data.errors);
        }
    })
    .catch(() => showToast("Failed to save GDPR option.", 'bg-danger'));
});

function openEditGdprModal(id, name) {
    clearValidationErrors('edit-gdpr-form');
    document.getElementById('edit-gdpr-id').value = id;
    document.getElementById('edit-gdpr-name').value = name;
    modals.editGdpr.show();
}

document.getElementById('edit-gdpr-form').addEventListener('submit', function(e) {
    e.preventDefault();
    clearValidationErrors('edit-gdpr-form');
    const id = document.getElementById('edit-gdpr-id').value;
    const formData = new FormData(this);
    
    fetch(`/admin/dropdown/gdpr/${id}/update`, {
        method: 'POST',
        headers: requestHeaders,
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(async res => {
        const data = await res.json();
        if (res.ok) {
            modals.editGdpr.hide();
            showToast(data.message);
            renderGdpr();
        } else {
            handleValidationErrors('edit-gdpr-form', data.errors);
        }
    })
    .catch(() => showToast("Failed to update GDPR option.", 'bg-danger'));
});

function toggleGdpr(id, checkbox) {
    fetch(`/admin/dropdown/gdpr/${id}/toggle`, {
        method: 'POST',
        headers: requestHeaders
    })
    .then(res => res.json())
    .then(data => {
        showToast(data.message);
        renderGdpr();
    })
    .catch(() => {
        checkbox.checked = !checkbox.checked;
        showToast("Failed to update status.", 'bg-danger');
    });
}

function deleteGdpr(id) {
    if (confirm("Are you sure you want to delete this GDPR compliance option?")) {
        fetch(`/admin/dropdown/gdpr/${id}/delete`, {
            method: 'POST',
            headers: requestHeaders
        })
        .then(res => res.json())
        .then(data => {
            showToast(data.message);
            renderGdpr();
        })
        .catch(() => showToast("Failed to delete option.", 'bg-danger'));
    }
}

// -------------------------------------------------------------
// D. Validation Errors UI Helper
// -------------------------------------------------------------
function handleValidationErrors(formId, errors) {
    if (!errors) return;
    Object.keys(errors).forEach(field => {
        const input = document.querySelector(`#${formId} [name="${field}"]`);
        const errorDiv = document.getElementById(`${formId}-error-${field}`);
        if (input) {
            input.classList.add('is-invalid');
        }
        if (errorDiv) {
            errorDiv.textContent = errors[field].join(' ');
            errorDiv.style.display = 'block';
        }
    });
}

function clearValidationErrors(formId) {
    const inputs = document.querySelectorAll(`#${formId} .form-control, #${formId} .form-select`);
    inputs.forEach(input => {
        input.classList.remove('is-invalid');
    });
    const errorDivs = document.querySelectorAll(`#${formId} .invalid-feedback`);
    errorDivs.forEach(div => {
        div.textContent = '';
        div.style.display = 'none';
    });
}

// -------------------------------------------------------------
// E. Search Filters Event Listeners (Client Side)
// -------------------------------------------------------------
document.getElementById('search-units').addEventListener('keyup', function(e) {
    const term = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#units-table tbody tr');
    rows.forEach(row => {
        const name = row.querySelector('.unit-name').textContent.toLowerCase();
        if (name.includes(term)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

document.getElementById('search-subunits').addEventListener('keyup', function(e) {
    filterSubUnits();
});

document.getElementById('search-gdpr').addEventListener('keyup', function(e) {
    const term = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#gdpr-table tbody tr');
    rows.forEach(row => {
        const name = row.querySelector('.gdpr-name').textContent.toLowerCase();
        if (name.includes(term)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Initial Invocations
document.addEventListener('DOMContentLoaded', () => {
    renderUnits();
    renderSubUnits();
    renderGdpr();
});
</script>
@endpush
