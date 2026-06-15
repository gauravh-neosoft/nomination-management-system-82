@extends('layouts.app')

@section('content')
<!-- SECTION D: NOMINATOR APPLICATION QUEUE -->
<div id="panel-queue" class="admin-panel bg-white p-4 rounded-4 border">
  <h2>Nominator Application Monitoring Queue</h2>
  <p class="text-secondary fs-09 mb-4">Master-detail live monitoring of events and nominator nomination lists.</p>

  <div class="row g-4">
    <!-- Master List (Left Column) -->
    <div class="col-lg-5">
      <div class="border rounded-4 p-3 bg-light">
        <h5 class="fw-bold mb-3 small">Active Nomination Feeds</h5>
        <div class="list-group" id="queue-master-list">
          <button class="list-group-item list-group-item-action active text-start py-3" onclick="showQueueDetail('Q3 Performance Awards', 'Sarah Miller', 'Hospitality event for department excellence performance.', 'Sarah Miller (sarah@nominator.com)', 'Completed Event', this)">
            <div class="d-flex justify-content-between align-items-center">
              <span class="fw-bold">Q3 Performance Awards</span>
              <span class="badge bg-light text-dark">Completed</span>
            </div>
            <small class="d-block mt-1">Nominator: Sarah Miller</small>
          </button>
          <button class="list-group-item list-group-item-action text-start py-3" onclick="showQueueDetail('Tech Summit 2026', 'John Doe', 'Corporate engineering and developer conference.', 'John Doe (john.doe@nominator.com)', 'Delivered', this)">
            <div class="d-flex justify-content-between align-items-center">
              <span class="fw-bold">Tech Summit 2026</span>
              <span class="badge bg-secondary">Delivered</span>
            </div>
            <small class="d-block mt-1">Nominator: John Doe</small>
          </button>
          <button class="list-group-item list-group-item-action text-start py-3" onclick="showQueueDetail('Leadership Retreat', 'Alice Smith', 'Annual executive strategic offsite event.', 'Alice Smith (alice@nominator.com)', 'Sent', this)">
            <div class="d-flex justify-content-between align-items-center">
              <span class="fw-bold">Leadership Retreat</span>
              <span class="badge bg-secondary">Sent</span>
            </div>
            <small class="d-block mt-1">Nominator: Alice Smith</small>
          </button>
        </div>
      </div>
    </div>

    <!-- Detail View & Status Panel (Right Column) -->
    <div class="col-lg-7">
      <div class="border rounded-4 p-4" id="queue-detail-pane">
        <h4 class="fw-bold mb-2" id="detail-event-title">Q3 Performance Awards</h4>
        <span class="badge bg-success mb-3 p-2 text-white" id="detail-delivery-badge">Completed Event</span>
        
        <h6 class="fw-bold text-dark mt-3">Event Details</h6>
        <p class="text-secondary small" id="detail-event-desc">Hospitality event for department excellence performance.</p>
        
        <hr />
        
        <h6 class="fw-bold text-dark">Nominator Details</h6>
        <table class="table table-borderless table-sm small">
          <tr>
            <td class="fw-semibold" style="width: 150px;">Nominator Name & ID:</td>
            <td id="detail-nominator-name">Sarah Miller (sarah@nominator.com)</td>
          </tr>
          <tr>
            <td class="fw-semibold">Invite Status Level:</td>
            <td>
              <select class="form-select form-select-sm d-inline-block w-auto py-0 invite-status-dropdown" id="detail-status-select" onchange="updateDeliveryBadge(this.value)">
                <option value="Sent">Sent</option>
                <option value="Delivered">Delivered</option>
                <option value="Registered">Registered</option>
                <option value="Do Not Contact (DNC)">Do Not Contact (DNC)</option>
                <option value="Completed Event" selected>Completed Event</option>
              </select>
            </td>
          </tr>
        </table>

        <!-- Custom invite status configuration form -->
        <div class="border rounded-3 p-3 bg-light mt-4">
          <label class="form-label fw-bold small">Configure New Invite Status Label</label>
          <div class="input-group input-group-sm">
            <input type="text" class="form-control" placeholder="e.g. Followup Sent" id="new-invite-status-input" />
            <button class="btn btn-primary bg-primary text-white" type="button" onclick="addNewInviteStatus()">Add Status Option</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
const inviteStatuses = ['Sent', 'Delivered', 'Registered', 'Do Not Contact (DNC)', 'Completed Event'];

function showQueueDetail(eventTitle, nominatorName, desc, fullNomText, status, itemBtn) {
    // Highlight list group item
    const items = document.querySelectorAll('#queue-master-list .list-group-item');
    items.forEach(i => i.classList.remove('active'));
    itemBtn.classList.add('active');

    // Update details pane
    document.getElementById('detail-event-title').textContent = eventTitle;
    document.getElementById('detail-event-desc').textContent = desc;
    document.getElementById('detail-nominator-name').textContent = fullNomText;
    
    // Update delivery status dropdown
    const select = document.getElementById('detail-status-select');
    select.value = status;

    updateDeliveryBadge(status);
}

function updateDeliveryBadge(status) {
    const badge = document.getElementById('detail-delivery-badge');
    badge.textContent = status;
    if (status === 'Sent') {
        badge.className = 'badge bg-primary mb-3 p-2 text-white';
    } else if (status === 'Delivered') {
        badge.className = 'badge bg-info mb-3 p-2 text-dark';
    } else if (status === 'Registered') {
        badge.className = 'badge bg-success mb-3 p-2 text-white';
    } else if (status === 'Do Not Contact (DNC)') {
        badge.className = 'badge bg-danger mb-3 p-2 text-white';
    } else {
        badge.className = 'badge bg-dark mb-3 p-2 text-white';
    }
    showToast(`Invite Delivery Status updated to "${status}".`);
}

function addNewInviteStatus() {
    const input = document.getElementById('new-invite-status-input');
    const newStatus = input.value.trim();
    if (newStatus) {
        if (!inviteStatuses.includes(newStatus)) {
            inviteStatuses.push(newStatus);
            
            // Append to dropdown options list
            const dropdown = document.getElementById('detail-status-select');
            const opt = document.createElement('option');
            opt.value = newStatus;
            opt.textContent = newStatus;
            dropdown.appendChild(opt);

            showToast(`Status "${newStatus}" added to Event OPS dropdown options.`);
            input.value = '';
        } else {
            alert("This invite status already exists.");
        }
    }
}
</script>
@endpush
