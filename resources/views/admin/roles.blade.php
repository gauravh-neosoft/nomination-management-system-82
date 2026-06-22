@extends('layouts.app')

@section('content')
<!-- SECTION A: ROLE & ACCESS MANAGEMENT -->
<div id="panel-roles" class="admin-panel bg-white p-4 rounded-4 border">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h2>Role & Access Management</h2>
      <p class="text-secondary fs-09">Configure access permissions, custom roles, and security groups.</p>
    </div>
    <button class="btn btn-primary btn-sm bg-primary" onclick="showAddRoleModal()"><i class="bi bi-plus-lg me-1"></i> Add Role</button>
  </div>

  <!-- Role table -->
  <div class="table-responsive mb-4 border rounded-3 bg-white">
    <table class="table table-hover align-middle mb-0" id="roles-table">
      <thead>
        <tr class="table-light">
          <th class="ps-4">Access Group / Role</th>
          <th>Description</th>
          <th>Permissions Configured</th>
          <th style="width: 150px">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($roles as $role)
        <tr>
          <td class="ps-4 fw-semibold">{{ $role->display_name }}</td>
          <td>do we require this column</td>
          <td>do we require this column</td>
          <td>
            @if($role->name === 'admin')
              <button class="btn btn-sm btn-link text-decoration-none py-0 text-secondary" onclick="event.stopPropagation(); alert('Access restricted: System Admin Role cannot be deleted.')">Delete</button>
            @else
              <button class="btn btn-sm btn-link text-decoration-none text-danger py-0" onclick="event.stopPropagation(); deleteRole({{ $role->id }}, this)">Delete</button>
            @endif
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
function showAddRoleModal() {
    const newRole = prompt("Enter the name of the new access role:");
    if (newRole && newRole.trim() !== '') {
        fetch("{{ route('admin-roles-store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name: newRole.trim() })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);
                // Reload the page to reflect DB state
                window.location.reload();
            } else {
                showToast(data.message || "Failed to create role.", "bg-danger");
            }
        })
        .catch(() => showToast("Failed to create role.", "bg-danger"));
    }
}

function deleteRole(id, btn) {
    if (confirm("Are you sure you want to delete this role?")) {
        fetch(`/admin/roles/${id}/delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);
                btn.closest('tr').remove();
            } else {
                showToast(data.message || "Failed to delete role.", "bg-danger");
            }
        })
        .catch(() => showToast("Failed to delete role.", "bg-danger"));
    }
}
</script>
@endpush
