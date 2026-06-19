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
            <input type="hidden" name="id" id="contact-id" />
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
                  <button type="button" onclick="resetContactForm()" class="btn btn-secondary text-nowrap">Reset</button>
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
                    <button onclick="editContact({{ json_encode($contact) }})" class="btn btn-sm text-primary p-1 me-2" title="Edit">
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
            <input type="hidden" name="id" id="domain-id" />
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
                  <button type="button" onclick="resetDomainForm()" class="btn btn-secondary text-nowrap">Reset</button>
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
                    <button onclick="editDomain({{ json_encode($domain) }})" class="btn btn-sm text-primary p-1 me-2" title="Edit">
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
function editContact(contact) {
    document.getElementById('contact-form-title').innerText = "Edit DNC Contact";
    document.getElementById('contact-id').value = contact.id;
    document.getElementById('contact-name').value = contact.name;
    document.getElementById('contact-email').value = contact.email;
    document.getElementById('contact-name').focus();
}

function resetContactForm() {
    document.getElementById('contact-form-title').innerText = "Add New DNC Contact";
    document.getElementById('contact-id').value = "";
    document.getElementById('contact-form').reset();
}

function editDomain(domain) {
    document.getElementById('domain-form-title').innerText = "Edit DNC Domain";
    document.getElementById('domain-id').value = domain.id;
    document.getElementById('domain-account-name').value = domain.account_name;
    document.getElementById('domain-name').value = domain.domain;
    document.getElementById('domain-account-name').focus();
}

function resetDomainForm() {
    document.getElementById('domain-form-title').innerText = "Add New DNC Domain";
    document.getElementById('domain-id').value = "";
    document.getElementById('domain-form').reset();
}
</script>
@endsection
