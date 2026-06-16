@extends('layouts.app')

@section('content')
<h2 class="mb-0">My Nominations</h2>
<p class="text-secondary">A list of all your submitted nominations across all events</p>

<!-- Filters -->
<div class="border rounded-3 p-2 my-3 bg-white">
  <div class="d-flex align-items-center gap-3">
    <!-- Search -->
    <div class="flex-grow-1">
      <div class="d-flex align-items-center">
        <i class="bi bi-search text-secondary me-2"></i>
        <input
          type="text"
          id="nominationSearch"
          class="form-control border-0 shadow-none p-0"
          placeholder="Search by nominee name or event name.."
          onkeyup="filterNominations()"
        />
      </div>
    </div>
  </div>
</div>
<!-- Filters End -->

<!-- Table -->
<div class="border rounded-3 p-2">
  <div class="table-responsive">
    <table class="table table-borderless align-middle" id="nominationsTable">
      <thead>
        <tr class="table-light">
          <th scope="col" style="min-width: 70px">Serial No.</th>
          <th scope="col" style="min-width: 200px">Event Name</th>
          <th scope="col" style="min-width: 200px">Nominee Name</th>
          <th scope="col" style="min-width: 200px">Email</th>
          <th scope="col" style="min-width: 150px">Company</th>
          <th scope="col" style="min-width: 150px">Job Title</th>
          <th scope="col" style="min-width: 100px">Job Level</th>
          <th scope="col" style="min-width: 150px">Invite Status</th>
          <th scope="col" style="min-width: 150px">Approval Status</th>
          <th scope="col" style="min-width: 150px">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($nominees as $nominee)
        <tr class="{{ $loop->iteration % 2 == 0 ? 'table-light' : '' }}" data-search-text="{{ strtolower($nominee->full_name . ' ' . $nominee->event_name) }}">
          <td>{{ $loop->iteration }}</td>
          <td class="fw-semibold">{{ $nominee->event_name }}</td>
          <td>{{ $nominee->full_name }}</td>
          <td>{{ $nominee->email }}</td>
          <td>{{ $nominee->company }}</td>
          <td>{{ $nominee->title }}</td>
          <td>{{ $nominee->job_level }}</td>
          <td>{{ $nominee->invite_status }}</td>
          <td>
            @if($nominee->approval_status === 'approved')
              <div class="w-fit badge rounded-pill bg-light-green text-success px-2 py-1 fw-normal d-flex align-items-center gap-2">
                <span class="rounded-circle bg-success d-inline-block" style="width: 7px; height: 7px"></span>
                Approved
              </div>
            @elseif($nominee->approval_status === 'pending')
              <div class="w-fit badge rounded-pill bg-light-yellow text-warning px-2 py-1 fw-normal d-flex align-items-center gap-2">
                <span class="rounded-circle bg-warning d-inline-block" style="width: 7px; height: 7px"></span>
                Pending
              </div>
            @else
              <div class="w-fit badge rounded-pill bg-light-red text-danger px-2 py-1 fw-normal d-flex align-items-center gap-2">
                <span class="rounded-circle bg-danger d-inline-block" style="width: 7px; height: 7px"></span>
                Rejected
              </div>
            @endif
          </td>
          <td>
            @if($nominee->approval_status === 'approved')
              <button class="btn btn-link text-primary text-decoration-none p-0 border-0" onclick="alert('Add remark clicked')">
                Add Remark
              </button>
            @else
              <button class="btn btn-link text-primary text-decoration-none p-0 border-0" onclick="alert('Edit nominee clicked')">
                Edit
              </button>
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="10" class="text-center text-muted py-4">You have not submitted any nominations yet.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<!-- Table ends -->

@push('scripts')
<script>
function filterNominations() {
    const query = document.getElementById('nominationSearch').value.toLowerCase();
    const rows = document.querySelectorAll('#nominationsTable tbody tr[data-search-text]');
    
    rows.forEach(row => {
        const searchText = row.getAttribute('data-search-text');
        if (searchText) {
            if (searchText.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
}
</script>
@endpush

@endsection
