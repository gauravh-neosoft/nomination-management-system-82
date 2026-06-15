@extends('layouts.app')

@section('content')
<!-- SECTION E: CONTACT MANAGEMENT -->
<div id="panel-contacts" class="admin-panel bg-white p-4 rounded-4 border">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h2>Nominator Contacts</h2>
      <p class="text-secondary fs-09">Manage profiles and directory listings of direct nominators.</p>
    </div>
    <div>
      <!-- CSV Import Simulator -->
      <input type="file" id="contacts-csv-file" class="d-none" onchange="simulateBulkImport(event)" />
      <button class="btn btn-sm btn-outline-primary" onclick="document.getElementById('contacts-csv-file').click()"><i class="bi bi-file-earmark-arrow-up"></i> Bulk Import CSV</button>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-striped align-middle" id="contacts-table">
      <thead>
        <tr class="table-light">
          <th>First Name</th>
          <th>Surname</th>
          <th>Email Address</th>
          <th>Contact</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Bruce</td>
          <td>Wayne</td>
          <td>bruce@waynecorp.com</td>
          <td>+1 555-0100</td>
          <td><button class="btn btn-sm btn-link text-danger p-0" onclick="deleteRow(this)">Delete</button></td>
        </tr>
        <tr>
          <td>Clark</td>
          <td>Kent</td>
          <td>clark@dailyplanet.com</td>
          <td>+1 555-0188</td>
          <td><button class="btn btn-sm btn-link text-danger p-0" onclick="deleteRow(this)">Delete</button></td>
        </tr>
        <tr>
          <td>Diana</td>
          <td>Prince</td>
          <td>diana@themyscira.org</td>
          <td>+1 555-0199</td>
          <td><button class="btn btn-sm btn-link text-danger p-0" onclick="deleteRow(this)">Delete</button></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection

@push('scripts')
<script>
function simulateBulkImport(e) {
    const file = e.target.files[0];
    if (file) {
        const tableBody = document.querySelector('#contacts-table tbody');
        const mockContacts = [
            { first: 'Tony', last: 'Stark', email: 'tony@starkcorp.com', phone: '+1 555-3000' },
            { first: 'Peter', last: 'Parker', email: 'peter.parker@dailybugle.com', phone: '+1 555-8499' }
        ];

        mockContacts.forEach(contact => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${contact.first}</td>
                <td>${contact.last}</td>
                <td class="contact-email">${contact.email}</td>
                <td>${contact.phone}</td>
                <td><button class="btn btn-sm btn-link text-danger p-0" onclick="deleteRow(this)">Delete</button></td>
            `;
            tableBody.appendChild(tr);
        });

        showToast(`Ingested ${mockContacts.length} contacts successfully from CSV!`);
    }
}

function deleteRow(btn) {
    if (confirm("Are you sure you want to delete this row?")) {
        btn.closest('tr').remove();
        showToast("Record deleted successfully.");
    }
}
</script>
@endpush
