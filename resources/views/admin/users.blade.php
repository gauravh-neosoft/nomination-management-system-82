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
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" {{ $user->status == 1 ? 'checked' : '' }} onchange="toggleUserStatus({{ $user->id }}, this)" />
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

@push('scripts')
<script>
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
