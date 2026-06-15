@extends('layouts.app')

@section('content')
<!-- SECTION B: USER DIRECTORY -->
<div id="panel-users" class="admin-panel bg-white p-4 rounded-4 border">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h2>User Directory</h2>
      <p class="text-secondary fs-09">Manage and review profile tiers across your organization.</p>
    </div>
    <button class="btn btn-outline-secondary btn-sm" onclick="alert('Exporting Directory...')"><i class="bi bi-download me-1"></i> Export list</button>
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr class="table-light">
          <th>First Name</th>
          <th>Surname</th>
          <th>Email Address</th>
          <th>Contact Number</th>
          <th>Role / Tier</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Yash</td>
          <td>Purkar</td>
          <td>yash.purkar@company.com</td>
          <td>+91 9876543210</td>
          <td><span class="badge bg-dark">Admin</span></td>
          <td><span class="badge bg-success" id="status-1">Active</span></td>
          <td>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" checked onchange="toggleUserStatus(1, this)" />
            </div>
          </td>
        </tr>
        <tr>
          <td>Gaurav</td>
          <td>Heda</td>
          <td>gaurav.heda@company.com</td>
          <td>+91 9999888877</td>
          <td><span class="badge bg-secondary">Unit SPOC</span></td>
          <td><span class="badge bg-success" id="status-2">Active</span></td>
          <td>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" checked onchange="toggleUserStatus(2, this)" />
            </div>
          </td>
        </tr>
        <tr>
          <td>Sarah</td>
          <td>Miller</td>
          <td>sarah.miller@company.com</td>
          <td>+1 555-0199</td>
          <td><span class="badge bg-info text-dark">Event OPS</span></td>
          <td><span class="badge bg-success" id="status-3">Active</span></td>
          <td>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" checked onchange="toggleUserStatus(3, this)" />
            </div>
          </td>
        </tr>
        <tr>
          <td>John</td>
          <td>Doe</td>
          <td>john.doe@nominator.com</td>
          <td>+1 555-0144</td>
          <td><span class="badge bg-light text-dark border">Nominator</span></td>
          <td><span class="badge bg-danger" id="status-4">Disabled</span></td>
          <td>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" onchange="toggleUserStatus(4, this)" />
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection

@push('scripts')
<script>
function toggleUserStatus(userId, checkbox) {
    const statusBadge = document.getElementById(`status-${userId}`);
    if (checkbox.checked) {
        statusBadge.textContent = 'Active';
        statusBadge.className = 'badge bg-success';
        showToast("User account has been enabled.");
    } else {
        statusBadge.textContent = 'Disabled';
        statusBadge.className = 'badge bg-danger';
        showToast("User account has been disabled.", 'bg-warning');
    }
}
</script>
@endpush
