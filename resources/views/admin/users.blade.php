@extends('layouts.app')

@section('content')
  <!-- Success Notification -->
  @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
          <div class="d-flex align-items-center">
              <i class="bi bi-check-circle-fill me-2 fs-5"></i>
              <div>{{ session('success') }}</div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
  @endif

<!-- SECTION B: USER DIRECTORY -->
<div id="panel-users" class="admin-panel bg-white p-4 rounded-4 border">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h2>User Directory</h2>
      <p class="text-secondary fs-09">Manage and review profile tiers across your organization.</p>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('admin-users-create') }}" class="btn btn-primary btn-sm bg-primary"><i class="bi bi-plus-lg me-1"></i> Add User</a>
      <button class="btn btn-outline-secondary btn-sm" onclick="alert('Exporting Directory...')"><i class="bi bi-download me-1"></i> Export list</button>
    </div>
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
        @foreach($users as $user)
        <tr>
          <td>{{ $user->name }}</td>
          <td>{{ $user->last_name ?: '-' }}</td>
          <td>{{ $user->email }}</td>
          <td>{{ $user->contact_no ?: '-' }}</td>
          <td>
            <span class="badge text-dark">
              {{ $user->role ? $user->role->display_name : 'No Role' }}
            </span>
          </td>
          <td>
            @if($user->status == 1)
              <span class="badge bg-success" id="status-{{ $user->id }}">Active</span>
            @else
              <span class="badge bg-danger" id="status-{{ $user->id }}">Inactive</span>
            @endif
          </td>
          <td>
            <div class="d-flex align-items-center gap-2">
              <button class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="openEditUserModal({
                  id: {{ $user->id }},
                  name: '{{ addslashes($user->name) }}',
                  last_name: '{{ addslashes($user->last_name) }}',
                  email: '{{ addslashes($user->email) }}',
                  contact_no: '{{ addslashes($user->contact_no) }}',
                  role_id: {{ $user->role_id }},
                  status: {{ $user->status }}
              })">Edit</button>
              <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" {{ $user->status == 1 ? 'checked' : '' }} onchange="toggleUserStatus({{ $user->id }}, this)" />
              </div>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header border-0 bg-light py-3">
        <h5 class="modal-title fw-bold">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4">
        <form id="edit-user-form" method="post" action="">
          @csrf
          <div class="row g-3">
            <!-- First Name -->
            <div class="col-md-6">
              <label class="form-label fw-bold small">First Name</label>
              <input type="text" name="name" id="edit-form-user-name" class="form-control form-control-sm" required placeholder="e.g. Jane" />
            </div>
            <!-- Last Name -->
            <div class="col-md-6">
              <label class="form-label fw-bold small">Last Name</label>
              <input type="text" name="last_name" id="edit-form-user-last-name" class="form-control form-control-sm" required placeholder="e.g. Miller" />
            </div>
            <!-- Email Address -->
            <div class="col-md-12">
              <label class="form-label fw-bold small">Email Address</label>
              <input type="email" name="email" id="edit-form-user-email" class="form-control form-control-sm" required placeholder="e.g. jane.miller@company.com" />
            </div>
            <!-- Contact Number -->
            <div class="col-md-12">
              <label class="form-label fw-bold small">Contact Number</label>
              <input type="text" name="contact_no" id="edit-form-user-contact" class="form-control form-control-sm" placeholder="e.g. +1234567890" />
            </div>
            <!-- Role Select -->
            <div class="col-md-6">
              <label class="form-label fw-bold small">Role / Access Level</label>
              <select name="role_id" id="edit-form-user-role" class="form-select form-select-sm" required>
                <option value="">Select Role...</option>
                @foreach($roles as $role)
                  <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                @endforeach
              </select>
            </div>
            <!-- Status Select -->
            <div class="col-md-6">
              <label class="form-label fw-bold small">Status</label>
              <select name="status" id="edit-form-user-status" class="form-select form-select-sm" required>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>
            
            <!-- Submit & Cancel Buttons -->
            <div class="col-12 text-end mt-4 pt-3 border-top">
              <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 me-2" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-sm btn-primary bg-primary rounded-3">Save Changes</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
let editUserModalInstance = null;

document.addEventListener('DOMContentLoaded', () => {
    editUserModalInstance = new bootstrap.Modal(document.getElementById('editUserModal'));
});

function openEditUserModal(userData) {
    const form = document.getElementById('edit-user-form');
    form.action = `/admin/users/${userData.id}/update`;
    
    document.getElementById('edit-form-user-name').value = userData.name;
    document.getElementById('edit-form-user-last-name').value = userData.last_name || '';
    document.getElementById('edit-form-user-email').value = userData.email;
    document.getElementById('edit-form-user-contact').value = userData.contact_no || '';
    document.getElementById('edit-form-user-role').value = userData.role_id;
    document.getElementById('edit-form-user-status').value = userData.status;
    
    editUserModalInstance.show();
}

function toggleUserStatus(userId, checkbox) {
    const statusBadge = document.getElementById(`status-${userId}`);
    
    fetch(`/admin/users/${userId}/toggle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.status == 1) {
                statusBadge.textContent = 'Active';
                statusBadge.className = 'badge bg-success';
                showToast("User account has been enabled.");
            } else {
                statusBadge.textContent = 'Inactive';
                statusBadge.className = 'badge bg-danger';
                showToast("User account has been disabled.", 'bg-warning');
            }
        } else {
            checkbox.checked = !checkbox.checked;
            showToast("Failed to update user status.", 'bg-danger');
        }
    })
    .catch(error => {
        checkbox.checked = !checkbox.checked;
        showToast("An error occurred while updating status.", 'bg-danger');
    });
}
</script>
@endpush
