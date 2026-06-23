@extends('layouts.app')

@section('content')
<h2 class="mb-0">DNC Contact</h2>
<p class="text-secondary">Add, edit, or delete blocked contacts and domains.</p>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if($errors->any())
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <ul class="mb-0">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<div class="border rounded-3 p-4 my-4 bg-white">
  <div class="underline-tabs">
    <ul class="nav nav-tabs border-0 mb-3" id="dncTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active text-black" id="contact-tab" data-bs-toggle="tab" data-bs-target="#tab-contacts" type="button" role="tab">
          Contact
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link text-black" id="domain-tab" data-bs-toggle="tab" data-bs-target="#tab-domains" type="button" role="tab">
          Domain
        </button>
      </li>
    </ul>

    <div class="tab-content" id="dncTabsContent">
      <!-- Contact Tab Content -->
      <div class="tab-pane fade show active" id="tab-contacts" role="tabpanel">
        <div class="border rounded-3 p-4 my-4 bg-light">
          <h5 class="fw-bold mb-3" id="contact-form-title">Add New DNC Contact</h5>
          <form action="{{ route('event-ops-save-dnc-contact') }}" method="POST" id="contact-form">
            @csrf
            <div class="row row-gap-3 align-items-end">
              <div class="col-12 col-sm-6 col-lg-4">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input
                  type="text"
                  name="name"
                  id="contact-name"
                  class="form-control rounded-3"
                  placeholder="Yash Purkar"
                  required
                />
              </div>

              <div class="col-12 col-sm-6 col-lg-4">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input
                  type="email"
                  name="email"
                  id="contact-email"
                  class="form-control rounded-3"
                  placeholder="yash@gmail.com"
                  required
                />
              </div>

              <div class="col-12 col-lg-4">
                <div class="d-flex gap-2">
                  <button type="submit" class="btn btn-primary w-100">Save Contact</button>
                  <button type="button" onclick="triggerContactUpload()" class="btn btn-dark w-100">Upload Excel/CSV</button>
                </div>
              </div>
            </div>
          </form>
        </div>

        <div class="table-responsive">
          <table class="table table-borderless align-middle">
            <thead>
              <tr class="table-light">
                <th scope="col" style="min-width: 70px" class="text-center">Serial No.</th>
                <th scope="col" style="min-width: 200px">Name</th>
                <th scope="col" style="min-width: 250px">Email</th>
                <th scope="col" style="min-width: 120px" class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($contacts as $index => $contact)
                <tr class="{{ $index % 2 == 1 ? 'table-light' : '' }}">
                  <td class="text-center">{{ $index + 1 }}</td>
                  <td class="fw-semibold">{{ $contact->name }}</td>
                  <td>{{ $contact->email }}</td>
                  <td class="text-center">
                    <button onclick="openEditContactModal({{ json_encode($contact) }})" class="btn btn-sm text-primary p-1 me-2" title="Edit">
                      <i class="bi bi-pencil-fill"></i>
                    </button>
                    <form action="{{ route('event-ops-delete-dnc-contact', $contact->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this DNC Contact?')">
                      @csrf
                      <button type="submit" class="btn btn-sm text-danger p-1" title="Delete">
                        <i class="bi bi-trash-fill"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center text-muted py-4">No DNC contacts found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Domain Tab Content -->
      <div class="tab-pane fade" id="tab-domains" role="tabpanel">
        <div class="border rounded-3 p-4 my-4 bg-light">
          <h5 class="fw-bold mb-3" id="domain-form-title">Add New DNC Domain</h5>
          <form action="{{ route('event-ops-save-dnc-domain') }}" method="POST" id="domain-form">
            @csrf
            <div class="row row-gap-3 align-items-end">
              <div class="col-12 col-sm-6 col-lg-4">
                <label class="form-label">Account Name <span class="text-danger">*</span></label>
                <input
                  type="text"
                  name="account_name"
                  id="domain-account-name"
                  class="form-control rounded-3"
                  placeholder="Adobe"
                  required
                />
              </div>

              <div class="col-12 col-sm-6 col-lg-4">
                <label class="form-label">Domain <span class="text-danger">*</span></label>
                <input
                  type="text"
                  name="domain"
                  id="domain-name"
                  class="form-control rounded-3"
                  placeholder="www.adobe.com"
                  required
                />
              </div>

              <div class="col-12 col-lg-4">
                <div class="d-flex gap-2">
                  <button type="submit" class="btn btn-primary w-100">Save Domain</button>
                  <button type="button" onclick="triggerDomainUpload()" class="btn btn-dark w-100">Upload Excel/CSV</button>
                </div>
              </div>
            </div>
          </form>
        </div>

        <div class="table-responsive">
          <table class="table table-borderless align-middle">
            <thead>
              <tr class="table-light">
                <th scope="col" style="min-width: 70px" class="text-center">Serial No.</th>
                <th scope="col" style="min-width: 200px">Account Name</th>
                <th scope="col" style="min-width: 250px">Domain</th>
                <th scope="col" style="min-width: 120px" class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($domains as $index => $domain)
                <tr class="{{ $index % 2 == 1 ? 'table-light' : '' }}">
                  <td class="text-center">{{ $index + 1 }}</td>
                  <td class="fw-semibold">{{ $domain->account_name }}</td>
                  <td>{{ $domain->domain }}</td>
                  <td class="text-center">
                    <button onclick="openEditDomainModal({{ json_encode($domain) }})" class="btn btn-sm text-primary p-1 me-2" title="Edit">
                      <i class="bi bi-pencil-fill"></i>
                    </button>
                    <form action="{{ route('event-ops-delete-dnc-domain', $domain->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this DNC Domain?')">
                      @csrf
                      <button type="submit" class="btn btn-sm text-danger p-1" title="Delete">
                        <i class="bi bi-trash-fill"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center text-muted py-4">No DNC domains found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function openEditContactModal(contact) {
    document.getElementById('edit-contact-id').value = contact.id;
    document.getElementById('edit-contact-name').value = contact.name;
    document.getElementById('edit-contact-email').value = contact.email;
    
    var editModal = new bootstrap.Modal(document.getElementById('editContactModal'));
    editModal.show();
}

function openEditDomainModal(domain) {
    document.getElementById('edit-domain-id').value = domain.id;
    document.getElementById('edit-domain-account-name').value = domain.account_name;
    document.getElementById('edit-domain-name').value = domain.domain;
    
    var editModal = new bootstrap.Modal(document.getElementById('editDomainModal'));
    editModal.show();
}

function triggerContactUpload() {
    document.getElementById('contact-upload-file-input').click();
}

function submitContactUpload() {
    document.getElementById('hidden-contact-upload-form').submit();
}

function triggerDomainUpload() {
    document.getElementById('domain-upload-file-input').click();
}

function submitDomainUpload() {
    document.getElementById('hidden-domain-upload-form').submit();
}
</script>

<!-- Edit DNC Contact Modal -->
<div class="modal fade" id="editContactModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('event-ops-save-dnc-contact') }}" method="POST" id="edit-contact-form">
        @csrf
        <input type="hidden" name="id" id="edit-contact-id" />
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold">Edit DNC Contact</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="edit-contact-name" class="form-control rounded-3" required />
          </div>
          <div class="mb-3">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" id="edit-contact-email" class="form-control rounded-3" required />
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit DNC Domain Modal -->
<div class="modal fade" id="editDomainModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('event-ops-save-dnc-domain') }}" method="POST" id="edit-domain-form">
        @csrf
        <input type="hidden" name="id" id="edit-domain-id" />
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold">Edit DNC Domain</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Account Name <span class="text-danger">*</span></label>
            <input type="text" name="account_name" id="edit-domain-account-name" class="form-control rounded-3" required />
          </div>
          <div class="mb-3">
            <label class="form-label">Domain <span class="text-danger">*</span></label>
            <input type="text" name="domain" id="edit-domain-name" class="form-control rounded-3" required />
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<form id="hidden-contact-upload-form" action="{{ route('event-ops-upload-dnc-contact') }}" method="POST" enctype="multipart/form-data" class="d-none">
  @csrf
  <input type="file" name="file" id="contact-upload-file-input" accept=".xlsx,.xls,.csv,.txt" onchange="submitContactUpload()" />
</form>

<form id="hidden-domain-upload-form" action="{{ route('event-ops-upload-dnc-domain') }}" method="POST" enctype="multipart/form-data" class="d-none">
  @csrf
  <input type="file" name="file" id="domain-upload-file-input" accept=".xlsx,.xls,.csv,.txt" onchange="submitDomainUpload()" />
</form>
@endsection
