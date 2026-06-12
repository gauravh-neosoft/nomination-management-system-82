@extends('layouts.app')

@section('content')
<!-- Toast Notification Container for AJAX Simulation Feedback -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1050">
  <div id="ajaxToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="ajaxToastMsg">
        Action completed successfully.
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<!-- ========================================== -->
<!-- 1. MAIN DASHBOARD OVERVIEW PANEL -->
<!-- ========================================== -->
<div id="panel-dashboard" class="admin-panel">
  <h2>Dashboard</h2>
  <p class="text-secondary fs-09 mb-4">Nomination platform performance and activity overview.</p>
  
  <!-- Dashboard Cards Grid -->
  <div class="row g-4 align-items-stretch">
    <!-- Card 1: Active Events -->
    <div class="col-12 col-sm-6 col-xl-3 d-flex">
      <div class="border-grey rounded-4 p-2 w-100 d-flex flex-column bg-white">
        <p class="sub-heading fw-semibold lh-1 p-2">Active Events</p>
        <div class="card border-0 flex-grow-1 d-flex flex-column">
          <div class="card-body mb-1 bg-light-orange rounded-4 rounded-bottom-0 d-flex flex-column flex-grow-1">
            <div class="d-flex justify-content-between">
              <h2 class="card-title fw-bold" id="metric-active-events">15</h2>
              <div class="icon-with-bg icon-bg-orange d-flex justify-content-center align-items-center">
                <i class="bi bi-calendar2-check text-white"></i>
              </div>
            </div>
            <p class="card-subtitle text-muted flex-grow-1 mt-2">
              <i class="bi bi-info-circle"></i>
              <span class="fs-09">2 Ending This Week</span>
            </p>
          </div>
          <div class="card-footer text-center rounded-4 rounded-top-0 bg-light-orange border-0">
            <a href="#events" class="btn btn-link p-0 text-decoration-none fw-semibold fs-09">
              Manage Events <i class="bi bi-arrow-up-right"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 2: Nominators Listed -->
    <div class="col-12 col-sm-6 col-xl-3 d-flex">
      <div class="border-grey rounded-4 p-2 w-100 d-flex flex-column bg-white">
        <p class="sub-heading fw-semibold lh-1 p-2">Nominators Listed</p>
        <div class="card border-0 flex-grow-1 d-flex flex-column">
          <div class="card-body mb-1 bg-light-green rounded-4 rounded-bottom-0 d-flex flex-column flex-grow-1">
            <div class="d-flex justify-content-between">
              <h2 class="card-title fw-bold" id="metric-nominators">48</h2>
              <div class="icon-with-bg icon-bg-green d-flex justify-content-center align-items-center">
                <i class="bi bi-people text-white"></i>
              </div>
            </div>
            <p class="card-subtitle text-muted flex-grow-1 mt-2">
              <i class="bi bi-info-circle"></i>
              <span class="fs-09">4 Access tiers configured</span>
            </p>
          </div>
          <div class="card-footer text-center rounded-4 rounded-top-0 bg-light-green border-0">
            <a href="#users" class="btn btn-link p-0 text-decoration-none fw-semibold text-nowrap fs-09">
              View Directory <i class="bi bi-arrow-up-right"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 3: Applications Received -->
    <div class="col-12 col-sm-6 col-xl-3 d-flex">
      <div class="border-grey rounded-4 p-2 w-100 d-flex flex-column bg-white">
        <p class="sub-heading fw-semibold lh-1 p-2">Applications Received</p>
        <div class="card border-0 flex-grow-1 d-flex flex-column">
          <div class="card-body mb-1 bg-light-purple rounded-4 rounded-bottom-0 d-flex flex-column flex-grow-1">
            <div class="d-flex justify-content-between">
              <h2 class="card-title fw-bold" id="metric-applications">184</h2>
              <div class="icon-with-bg icon-bg-purple d-flex justify-content-center align-items-center">
                <i class="bi bi-file-earmark-text text-white"></i>
              </div>
            </div>
            <p class="card-subtitle text-muted flex-grow-1 mt-2">
              <i class="bi bi-info-circle"></i>
              <span class="fs-09">34 Pending Review State</span>
            </p>
          </div>
          <div class="card-footer text-center rounded-4 rounded-top-0 bg-light-purple border-0">
            <a href="#queue" class="btn btn-link p-0 text-decoration-none fw-semibold fs-09">
              Monitor Queue <i class="bi bi-arrow-up-right"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 4: Recent Admin Activities -->
    <div class="col-12 col-sm-6 col-xl-3 d-flex">
      <div class="w-100 d-flex flex-column h-100 min-h-0 bg-white">
        <p class="fw-bold lh-1 mb-2">Recent System Logs</p>
        <div class="border rounded-4 px-3 py-2 flex-grow-1 overflow-y-auto overflow-x-hidden timeline-content-box" style="max-height: 160px;">
          <ul class="timeline-with-icons mb-0" style="padding-left: 20px; font-size: 0.85rem;">
            <li class="timeline-item mb-2" style="list-style-type: none; position: relative;">
              <span class="timeline-icon d-flex justify-content-center align-items-center timeline-approved" style="position: absolute; left: -25px; top: 2px;">
                <i class="bi bi-check-lg text-success"></i>
              </span>
              <p class="fw-bold mb-0">Domain Limit Updated</p>
              <small class="text-secondary">Cisco domain cap increased to 10.</small>
            </li>
            <li class="timeline-item mb-2" style="list-style-type: none; position: relative;">
              <span class="timeline-icon d-flex justify-content-center align-items-center timeline-draft" style="position: absolute; left: -25px; top: 2px;">
                <i class="bi bi-pencil text-warning"></i>
              </span>
              <p class="fw-bold mb-0">Role Permission Changed</p>
              <small class="text-secondary">Unit SPOC edit permission activated.</small>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Summary Details Table Area -->
  <div class="border mt-4 rounded-3 p-3 bg-white">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <p class="fw-bold lh-1 mb-0">System Activity Monitoring</p>
      <span class="badge bg-secondary">Live Status</span>
    </div>
    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle">
        <thead>
          <tr class="table-light">
            <th class="fs-09 fw-semibold">Task Area</th>
            <th class="fs-09 fw-semibold">Description</th>
            <th class="fs-09 fw-semibold">Access Level</th>
            <th class="fs-09 fw-semibold">Operational Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Role Assignment</td>
            <td>Group mapping and access security</td>
            <td>Admin Only</td>
            <td><span class="badge bg-success bg-opacity-75 text-white">Configured</span></td>
          </tr>
          <tr>
            <td>Exclusion Rules</td>
            <td>Blocklists checking and validation engine</td>
            <td>Event OPS / Admin</td>
            <td><span class="badge bg-success bg-opacity-75 text-white">Active</span></td>
          </tr>
          <tr>
            <td>Domain Boundaries</td>
            <td>Infosys, NeoSoft, Adobe caps</td>
            <td>Admin Only</td>
            <td><span class="badge bg-warning text-dark">Monitored</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ========================================== -->
<!-- SECTION A: ROLE & ACCESS MANAGEMENT -->
<!-- ========================================== -->
<div id="panel-roles" class="admin-panel d-none bg-white p-4 rounded-4 border">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h2>Role & Access Management</h2>
      <p class="text-secondary fs-09">Configure access permissions, custom roles, and security groups.</p>
    </div>
    <button class="btn btn-primary btn-sm bg-primary" onclick="showAddRoleModal()"><i class="bi bi-plus-lg me-1"></i> Add Role</button>
  </div>

  <!-- Role table -->
  <div class="table-responsive mb-4">
    <table class="table table-hover align-middle" id="roles-table">
      <thead>
        <tr class="table-light">
          <th>Access Group / Role</th>
          <th>Description</th>
          <th>Permissions Configured</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr onclick="selectRole('Admin', 'Full system access and master domain boundary administration.')" style="cursor:pointer;" class="table-active">
          <td class="fw-semibold">Admin</td>
          <td>Full system access and master domain boundaries</td>
          <td><span class="badge bg-primary">All Modules</span></td>
          <td>
            <button class="btn btn-sm btn-link text-decoration-none py-0" onclick="event.stopPropagation(); alert('Access restricted: System Admin Role cannot be deleted.')">Delete</button>
          </td>
        </tr>
        <tr onclick="selectRole('Event OPS', 'Manages event queues, imports contacts, and issues invitations.')" style="cursor:pointer;">
          <td class="fw-semibold">Event OPS</td>
          <td>Manages event queues, imports contacts, and issues invitations</td>
          <td><span class="badge bg-secondary">Events, Queue, Contacts</span></td>
          <td>
            <button class="btn btn-sm btn-link text-decoration-none text-danger py-0" onclick="event.stopPropagation(); deleteRow(this)">Delete</button>
          </td>
        </tr>
        <tr onclick="selectRole('Unit SPOC', 'Department and business unit nomination approvals.')" style="cursor:pointer;">
          <td class="fw-semibold">Unit SPOC</td>
          <td>Department and business unit nomination approvals</td>
          <td><span class="badge bg-secondary">Dashboard, Queue</span></td>
          <td>
            <button class="btn btn-sm btn-link text-decoration-none text-danger py-0" onclick="event.stopPropagation(); deleteRow(this)">Delete</button>
          </td>
        </tr>
        <tr onclick="selectRole('Nominator', 'Direct event nomination submission access.')" style="cursor:pointer;">
          <td class="fw-semibold">Nominator</td>
          <td>Direct event nomination submission access</td>
          <td><span class="badge bg-secondary">Dashboard, Events</span></td>
          <td>
            <button class="btn btn-sm btn-link text-decoration-none text-danger py-0" onclick="event.stopPropagation(); deleteRow(this)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Permissions Configurator Form -->
  <div class="border rounded-4 p-3 bg-light-orange bg-opacity-25 mt-4">
    <h5 class="fw-bold mb-3" id="selected-role-name">Configure Permissions for Admin</h5>
    <div class="row g-3">
      <div class="col-md-6 col-lg-4">
        <div class="form-check form-switch">
          <input class="form-check-input permission-toggle" type="checkbox" id="perm-dashboard" checked />
          <label class="form-check-label fw-semibold" for="perm-dashboard">Access Dashboard</label>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="form-check form-switch">
          <input class="form-check-input permission-toggle" type="checkbox" id="perm-users" checked />
          <label class="form-check-label fw-semibold" for="perm-users">Manage Users</label>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="form-check form-switch">
          <input class="form-check-input permission-toggle" type="checkbox" id="perm-events" checked />
          <label class="form-check-label fw-semibold" for="perm-events">Manage Events</label>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="form-check form-switch">
          <input class="form-check-input permission-toggle" type="checkbox" id="perm-queue" checked />
          <label class="form-check-label fw-semibold" for="perm-queue">Monitor Queues</label>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="form-check form-switch">
          <input class="form-check-input permission-toggle" type="checkbox" id="perm-contacts" checked />
          <label class="form-check-label fw-semibold" for="perm-contacts">Manage Contacts</label>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="form-check form-switch">
          <input class="form-check-input permission-toggle" type="checkbox" id="perm-cms" checked />
          <label class="form-check-label fw-semibold" for="perm-cms">Edit CMS Content</label>
        </div>
      </div>
    </div>
    
    <div class="mt-4 d-flex justify-content-between align-items-center">
      <button class="btn btn-primary btn-sm bg-primary" onclick="savePermissions()">Save Permissions</button>
      <span class="text-secondary fs-08" id="role-tracker-banner">Last updated by Yash Purkar at 2026-06-12 16:20:00</span>
    </div>
  </div>
</div>

<!-- ========================================== -->
<!-- SECTION B: USER DIRECTORY -->
<!-- ========================================== -->
<div id="panel-users" class="admin-panel d-none bg-white p-4 rounded-4 border">
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

<!-- ========================================== -->
<!-- SECTION C: EVENT MANAGEMENT DASHBOARD -->
<!-- ========================================== -->
<div id="panel-events" class="admin-panel d-none bg-white p-4 rounded-4 border">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h2>Event Management</h2>
      <p class="text-secondary fs-09">Deploy new hospitality events and configure nomination targets.</p>
    </div>
    <button class="btn btn-primary btn-sm bg-primary" onclick="toggleEventForm()"><i class="bi bi-calendar-plus me-1"></i> New Event</button>
  </div>

  <!-- Event Creation Form Section -->
  <div id="event-creation-form" class="border rounded-4 p-4 mb-4 bg-light d-none">
    <h5 class="fw-bold mb-3">Create New Event</h5>
    <form id="new-event-form" onsubmit="saveNewEvent(event)">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-bold small">Event Name</label>
          <input type="text" class="form-control form-control-sm" required id="form-event-name" />
        </div>
        <div class="col-md-6">
          <label class="form-label fw-bold small">Event Type</label>
          <select class="form-select form-select-sm" required id="form-event-type">
            <option value="Hospitality">Hospitality</option>
            <option value="Non-Hospitality">Non-Hospitality</option>
          </select>
        </div>
        <div class="col-12">
          <label class="form-label fw-bold small">Description</label>
          <textarea class="form-control form-control-sm" rows="2" required id="form-event-desc"></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-bold small">Event Banner Image</label>
          <input type="file" class="form-control form-control-sm" id="form-event-banner" />
        </div>
        <div class="col-md-6">
          <label class="form-label fw-bold small">Event Timeline (Date range / Date)</label>
          <input type="text" class="form-control form-control-sm" placeholder="e.g. Dec 1, 2026 - Dec 5, 2026" required id="form-event-timeline" />
        </div>
        <div class="col-md-6">
          <label class="form-label fw-bold small">Event Address</label>
          <input type="text" class="form-control form-control-sm" required id="form-event-address" />
        </div>
        <div class="col-md-6">
          <label class="form-label fw-bold small">Event Head Contact Details</label>
          <input type="text" class="form-control form-control-sm" placeholder="e.g. head@events.com" required id="form-event-head" />
        </div>
        <div class="col-md-6">
          <label class="form-label fw-bold small">Assigned Nominators (Select multiple)</label>
          <select class="form-select form-select-sm" multiple id="form-event-nominators" style="height: 60px;">
            <option value="John Doe">John Doe (john.doe@nominator.com)</option>
            <option value="Alice Smith">Alice Smith (alice@nominator.com)</option>
            <option value="Sarah Miller">Sarah Miller (sarah@nominator.com)</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-bold small">Max Nominees per Form</label>
          <input type="number" class="form-control form-control-sm" required value="10" id="form-event-max-nominees" />
        </div>
        <div class="col-12 text-end">
          <button type="button" class="btn btn-sm btn-outline-secondary me-2" onclick="toggleEventForm()">Cancel</button>
          <button type="submit" class="btn btn-sm btn-primary bg-primary">Create Event</button>
        </div>
      </div>
    </form>
  </div>

  <!-- Event List Table -->
  <div class="table-responsive">
    <table class="table table-hover align-middle" id="events-table">
      <thead>
        <tr class="table-light">
          <th>Event Name</th>
          <th>Event Type</th>
          <th>Timeline</th>
          <th>Max Nominees</th>
          <th>Nomination State</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="fw-semibold">Q3 Performance Awards</td>
          <td>Hospitality</td>
          <td>Dec 1, 2026</td>
          <td>5</td>
          <td><span class="badge bg-info text-dark">Applications Processed</span></td>
          <td>
            <!-- Disabled button safeguard rule: Nominations are processed -->
            <button class="btn btn-sm btn-outline-secondary py-0 px-2" disabled title="Nominator has already processed rows.">Edit</button>
            <button class="btn btn-sm btn-outline-danger py-0 px-2" disabled title="Nominator has already processed rows.">Delete</button>
          </td>
        </tr>
        <tr>
          <td class="fw-semibold">Tech Summit 2026</td>
          <td>Non-Hospitality</td>
          <td>Dec 15, 2026</td>
          <td>15</td>
          <td><span class="badge bg-warning text-dark">Awaiting Entries</span></td>
          <td>
            <button class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="alert('Editing Tech Summit 2026...')">Edit</button>
            <button class="btn btn-sm btn-outline-danger py-0 px-2" onclick="softDeleteEvent(this)">Delete</button>
          </td>
        </tr>
        <tr>
          <td class="fw-semibold">Leadership Retreat</td>
          <td>Hospitality</td>
          <td>Jan 10, 2027</td>
          <td>10</td>
          <td><span class="badge bg-warning text-dark">Awaiting Entries</span></td>
          <td>
            <button class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="alert('Editing Leadership Retreat...')">Edit</button>
            <button class="btn btn-sm btn-outline-danger py-0 px-2" onclick="softDeleteEvent(this)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- ========================================== -->
<!-- SECTION D: NOMINATOR APPLICATION QUEUE -->
<!-- ========================================== -->
<div id="panel-queue" class="admin-panel d-none bg-white p-4 rounded-4 border">
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

<!-- ========================================== -->
<!-- SECTION E: CONTACT MANAGEMENT & EXCLUSION LIST -->
<!-- ========================================== -->
<div id="panel-contacts" class="admin-panel d-none bg-white p-4 rounded-4 border">
  <div class="row g-4">
    <!-- Nominator Contacts (Left) -->
    <div class="col-xl-7">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Nominator Contacts</h2>
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

    <!-- Exclusion List Management (Right) -->
    <div class="col-xl-5">
      <div class="border rounded-4 p-4 bg-light">
        <h4 class="fw-bold mb-3">Exclusion List Hub</h4>
        
        <!-- Warning alert banner explicitly required -->
        <div class="alert alert-danger d-flex align-items-start shadow-sm mb-3">
          <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
          <div>
            <strong class="d-block">Names added to the exclusion list can never be nominated for events.</strong>
          </div>
        </div>

        <div class="border border-dashed rounded-4 p-4 text-center bg-white mb-3" style="border: 2px dashed #ccc;">
          <i class="bi bi-shield-x fs-1 text-danger"></i>
          <p class="small text-muted mt-2">Drag and drop unsubscribed emails CSV file here</p>
          <input type="file" id="exclusion-csv-input" class="form-control form-control-sm" onchange="simulateExclusionUpload(event)" />
        </div>

        <h6 class="fw-bold text-dark mt-4">Simulate Exclusion Check</h6>
        <div class="input-group input-group-sm">
          <input type="email" class="form-control" id="exclusion-test-email" placeholder="test@email.com" />
          <button class="btn btn-danger text-white" type="button" onclick="testExclusionEmail()">Check Blocklist</button>
        </div>
        <div class="mt-2 text-danger small fw-semibold d-none" id="exclusion-alert-box"></div>
      </div>
    </div>
  </div>
</div>

<!-- ========================================== -->
<!-- SECTION F: MASTER DATA MANAGEMENT (MDM) -->
<!-- ========================================== -->
<div id="panel-mdm" class="admin-panel d-none bg-white p-4 rounded-4 border">
  <h2>Master Data Management (MDM Hub)</h2>
  <p class="text-secondary fs-09">Ingest system records with historical duplication screening verification.</p>

  <!-- Duplicate prevention alert -->
  <div class="alert alert-warning d-flex align-items-center shadow-sm mb-4">
    <i class="bi bi-shield-exclamation me-2 fs-4"></i>
    <div>
      System matches incoming file strings against historical database indexes. Only unique new records are saved; duplicate entries will be flagged with a warning badge.
    </div>
  </div>

  <div class="row g-4">
    <div class="col-md-5">
      <div class="border border-dashed rounded-4 p-4 text-center bg-light" style="border: 2px dashed #ccc;">
        <i class="bi bi-cloud-arrow-up fs-1 text-primary"></i>
        <h6 class="fw-bold mt-2">Upload Master Sheet</h6>
        <p class="text-muted small">Ingest CSV or Excel datasets for automated deduplication parsing.</p>
        <input type="file" class="form-control form-control-sm" onchange="simulateMDMUpload(event)" />
      </div>
    </div>
    
    <div class="col-md-7">
      <div class="border rounded-4 p-3 bg-white" style="min-height: 200px;">
        <h6 class="fw-bold mb-3 small">Ingestion Status Logs</h6>
        <div class="table-responsive">
          <table class="table table-sm align-middle" id="mdm-log-table">
            <thead>
              <tr class="table-light">
                <th>Record Ingested</th>
                <th>Validation Verification Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>gaurav.heda@company.com</td>
                <td><span class="badge bg-warning text-dark">Duplicate - Ignored</span></td>
              </tr>
              <tr>
                <td>tony.stark@starkindustries.com</td>
                <td><span class="badge bg-success">Unique - Saved</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ========================================== -->
<!-- SECTION G: CONTENT CUSTOMIZATION PANEL (CMS) -->
<!-- ========================================== -->
<div id="panel-cms" class="admin-panel d-none bg-white p-4 rounded-4 border">
  <h2>Content Customization Panel (CMS)</h2>
  <p class="text-secondary fs-09 mb-4">Configure public-facing templates and static legal document sections.</p>

  <form onsubmit="saveCMSContent(event)">
    <div class="row g-4">
      <div class="col-md-6">
        <label class="form-label fw-bold small">Rewrite Public FAQs</label>
        <textarea class="form-control form-control-sm" rows="3" id="cms-faqs" required>Q1: How do I submit a nomination?
A1: Click nominations tab, enter email, details and press apply.

Q2: Who is eligible for hospitality events?
A2: All active full-time platform users mapped in the directory.</textarea>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-bold small">Modify Informational "About Us" Content</label>
        <textarea class="form-control form-control-sm" rows="3" id="cms-about" required>Nomination Management System is deployed and managed by Events COE to simplify organizational delegation of corporate events.</textarea>
      </div>
      <div class="col-12">
        <label class="form-label fw-bold small">Privacy Policies & Legal Notices</label>
        <textarea class="form-control form-control-sm" rows="4" id="cms-privacy" required>Privacy Notice:
All information collected is processed in compliance with organization IT policies and enterprise boundary access controls.</textarea>
      </div>
      <div class="col-12 text-end">
        <button type="submit" class="btn btn-primary btn-sm bg-primary px-4">Publish Content Changes</button>
      </div>
    </div>
  </form>
</div>

<!-- ========================================== -->
<!-- SECTION H: REPORTS ENGINE WORKSPACE -->
<!-- ========================================== -->
<div id="panel-reports" class="admin-panel d-none bg-white p-4 rounded-4 border">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h2>Reports Engine Workspace</h2>
      <p class="text-secondary fs-09">Expose operational dashboard queries and export spreadsheets.</p>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-success btn-sm bg-success text-white" onclick="alert('Exporting Report to CSV...')"><i class="bi bi-file-earmark-spreadsheet me-1"></i> Export to CSV</button>
      <button class="btn btn-primary btn-sm bg-primary" onclick="alert('Exporting Report to Excel...')"><i class="bi bi-file-earmark-excel me-1"></i> Export to Excel</button>
    </div>
  </div>

  <div class="row g-4">
    <!-- Query Table 1: Total nominators nominated for single event -->
    <div class="col-md-4">
      <div class="border rounded-4 p-3 bg-white h-100">
        <h6 class="fw-bold mb-3 small">Nominator Nominated / Event</h6>
        <div class="table-responsive">
          <table class="table table-striped table-sm align-middle">
            <thead>
              <tr class="table-light">
                <th>Event Name</th>
                <th class="text-end">Count</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Tech Summit 2026</td>
                <td class="text-end">18</td>
              </tr>
              <tr>
                <td>Q3 Performance</td>
                <td class="text-end">28</td>
              </tr>
              <tr>
                <td>Leadership Offsite</td>
                <td class="text-end">12</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Query Table 2: Total number of events created -->
    <div class="col-md-4">
      <div class="border rounded-4 p-3 bg-white h-100">
        <h6 class="fw-bold mb-3 small">Events Configured</h6>
        <div class="table-responsive">
          <table class="table table-striped table-sm align-middle">
            <thead>
              <tr class="table-light">
                <th>Type</th>
                <th class="text-end">Total</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Hospitality</td>
                <td class="text-end">8</td>
              </tr>
              <tr>
                <td>Non-Hospitality</td>
                <td class="text-end">12</td>
              </tr>
              <tr class="table-info">
                <td>Total</td>
                <td class="text-end">20</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Query Table 3: Total number of nominators contact records -->
    <div class="col-md-4">
      <div class="border rounded-4 p-3 bg-white h-100">
        <h6 class="fw-bold mb-3 small">Nominator Contacts Database</h6>
        <div class="table-responsive">
          <table class="table table-striped table-sm align-middle">
            <thead>
              <tr class="table-light">
                <th>Tier</th>
                <th class="text-end">Contacts</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Active</td>
                <td class="text-end">450</td>
              </tr>
              <tr>
                <td>Unsubscribed</td>
                <td class="text-end">24</td>
              </tr>
              <tr>
                <td>Exclusion Blocked</td>
                <td class="text-end">12</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ========================================== -->
<!-- SECTION I: ENTERPRISE DOMAIN MANAGEMENT PANEL -->
<!-- ========================================== -->
<div id="panel-domain" class="admin-panel d-none bg-white p-4 rounded-4 border">
  <h2>Enterprise Domain Management Panel</h2>
  <p class="text-secondary fs-09 mb-4">Set access controls and calculate enrollment capacities across accounts.</p>

  <div class="row g-4">
    <!-- Domain Setup Form -->
    <div class="col-lg-6">
      <div class="border rounded-4 p-4">
        <h5 class="fw-bold mb-3 small">Configure Account Access</h5>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-bold small">Enterprise Boundary Name</label>
            <select class="form-select form-select-sm" id="domain-enterprise-name" onchange="updateCalculatorLimits()">
              <option value="Infosys">Infosys</option>
              <option value="NeoSoft">NeoSoft</option>
              <option value="Adobe">Adobe</option>
              <option value="Cisco">Cisco</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold small">Domain Access Level</label>
            <select class="form-select form-select-sm" id="domain-access-level">
              <option value="Full Access">Full Access</option>
              <option value="Restricted">Restricted</option>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label fw-bold small">Set Domain Max Enrollment Cap</label>
            <input type="number" class="form-control form-control-sm" id="domain-capping-limit" value="10" oninput="updateCalculatorLimits()" />
          </div>
        </div>
      </div>
    </div>

    <!-- Account Boundary Calculator UI -->
    <div class="col-lg-6">
      <div class="border rounded-4 p-4 bg-light">
        <h5 class="fw-bold mb-3 small">Account Boundary Capacity Calculator</h5>
        
        <div class="row g-3">
          <div class="col-6">
            <small class="text-secondary d-block">Current Active State count (Sent/Accepted):</small>
            <span class="fs-4 fw-bold text-primary" id="calc-active-state">4</span>
          </div>
          <div class="col-6">
            <small class="text-secondary d-block">Max Domain Capping Limit:</small>
            <span class="fs-4 fw-bold text-dark" id="calc-cap-limit">10</span>
          </div>
          
          <div class="col-12">
            <label class="form-label fw-bold small">Simulate Nomination Entries Upload count</label>
            <input type="number" class="form-control form-control-sm" id="calc-upload-count" value="5" />
          </div>
          
          <div class="col-12">
            <button class="btn btn-sm btn-primary bg-primary px-3 w-100" onclick="calculateAccountBoundaryLimit()">Validate Nomination Submission</button>
          </div>
        </div>

        <!-- Validation failure notification banner -->
        <div class="alert alert-danger mt-3 d-none" id="calc-alert-banner">
          <i class="bi bi-x-octagon-fill me-2 fs-5"></i>
          <span id="calc-alert-msg">Validation Alert</span>
        </div>
        
        <!-- Validation success banner -->
        <div class="alert alert-success mt-3 d-none" id="calc-success-banner">
          <i class="bi bi-check-circle-fill me-2 fs-5"></i>
          <span>Validation success. Allocation falls within domain account boundary constraints.</span>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Dynamic single page panel navigation handler
    const panels = document.querySelectorAll('.admin-panel');
    const menuLinks = document.querySelectorAll('#admin-sidebar-menu .nav-link');

    function showPanel(hash) {
        if (!hash) hash = '#dashboard';
        const rawHash = hash.split('?')[0]; // discard query parameters if any
        const targetId = 'panel-' + rawHash.replace('#', '');
        
        let found = false;
        panels.forEach(panel => {
            if (panel.id === targetId) {
                panel.classList.remove('d-none');
                found = true;
            } else {
                panel.classList.add('d-none');
            }
        });

        // If target panel not found, fallback to dashboard
        if (!found) {
            document.getElementById('panel-dashboard').classList.remove('d-none');
        }

        menuLinks.forEach(link => {
            if (link.getAttribute('href') === rawHash) {
                link.classList.add('active-nav-link');
            } else {
                link.classList.remove('active-nav-link');
            }
        });
    }

    window.addEventListener('hashchange', () => {
        showPanel(window.location.hash);
    });

    showPanel(window.location.hash);
});

// Toast notification helper
function showToast(msg, bgClass = 'bg-success') {
    const toastEl = document.getElementById('ajaxToast');
    const toastMsg = document.getElementById('ajaxToastMsg');
    toastEl.className = `toast align-items-center text-white ${bgClass} border-0`;
    toastMsg.textContent = msg;
    const toast = new bootstrap.Toast(toastEl);
    toast.show();
}

// Global simulated lists
const exclusionEmails = ['blocked@example.com', 'spam@domain.com', 'blacklist@infosys.com'];
const inviteStatuses = ['Sent', 'Delivered', 'Registered', 'Do Not Contact (DNC)', 'Completed Event'];

// ==========================================
// SECTION A LOGIC (Role Management)
// ==========================================
let currentRole = 'Admin';
function selectRole(roleName, desc) {
    currentRole = roleName;
    document.getElementById('selected-role-name').textContent = `Configure Permissions for ${roleName}`;
    
    // Highlight table row
    const rows = document.querySelectorAll('#roles-table tbody tr');
    rows.forEach(r => {
        if (r.cells[0].textContent.trim() === roleName) {
            r.classList.add('table-active');
        } else {
            r.classList.remove('table-active');
        }
    });

    // Simulate permissions check state based on role
    const toggles = document.querySelectorAll('.permission-toggle');
    toggles.forEach(t => {
        if (roleName === 'Admin') {
            t.checked = true;
        } else if (roleName === 'Event OPS') {
            t.checked = ['perm-dashboard', 'perm-events', 'perm-queue', 'perm-contacts'].includes(t.id);
        } else if (roleName === 'Unit SPOC') {
            t.checked = ['perm-dashboard', 'perm-queue'].includes(t.id);
        } else {
            t.checked = ['perm-dashboard', 'perm-events'].includes(t.id);
        }
    });
}

function savePermissions() {
    const timestamp = new Date().toISOString().slice(0, 19).replace('T', ' ');
    document.getElementById('role-tracker-banner').textContent = `Last updated by Yash Purkar at ${timestamp}`;
    showToast(`Permissions updated for ${currentRole} successfully!`);
}

function showAddRoleModal() {
    const newRole = prompt("Enter the name of the new access role:");
    if (newRole) {
        const tableBody = document.querySelector('#roles-table tbody');
        const tr = document.createElement('tr');
        tr.style.cursor = 'pointer';
        tr.onclick = () => selectRole(newRole, 'Custom role permissions set.');
        tr.innerHTML = `
            <td class="fw-semibold">${newRole}</td>
            <td>Custom application access role</td>
            <td><span class="badge bg-secondary">Custom</span></td>
            <td><button class="btn btn-sm btn-link text-danger py-0" onclick="event.stopPropagation(); deleteRow(this)">Delete</button></td>
        `;
        tableBody.appendChild(tr);
        showToast(`Access role "${newRole}" created successfully.`);
    }
}

function deleteRow(btn) {
    if (confirm("Are you sure you want to delete this row?")) {
        btn.closest('tr').remove();
        showToast("Record deleted successfully.");
    }
}

// ==========================================
// SECTION B LOGIC (User Directory)
// ==========================================
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

// ==========================================
// SECTION C LOGIC (Event Management)
// ==========================================
function toggleEventForm() {
    const form = document.getElementById('event-creation-form');
    form.classList.toggle('d-none');
}

function saveNewEvent(e) {
    e.preventDefault();
    const name = document.getElementById('form-event-name').value;
    const type = document.getElementById('form-event-type').value;
    const timeline = document.getElementById('form-event-timeline').value;
    const maxNom = document.getElementById('form-event-max-nominees').value;

    const tableBody = document.querySelector('#events-table tbody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td class="fw-semibold">${name}</td>
        <td>${type}</td>
        <td>${timeline}</td>
        <td>${maxNom}</td>
        <td><span class="badge bg-warning text-dark">Awaiting Entries</span></td>
        <td>
            <button class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="alert('Editing...')">Edit</button>
            <button class="btn btn-sm btn-outline-danger py-0 px-2" onclick="softDeleteEvent(this)">Delete</button>
        </td>
    `;
    tableBody.appendChild(tr);

    // Update active events count
    const eventsMetric = document.getElementById('metric-active-events');
    eventsMetric.textContent = parseInt(eventsMetric.textContent) + 1;

    document.getElementById('new-event-form').reset();
    toggleEventForm();
    showToast("Event created and configured successfully!");
}

function softDeleteEvent(btn) {
    if (confirm("Are you sure you want to archive/delete this event?")) {
        const row = btn.closest('tr');
        row.style.opacity = '0.5';
        btn.disabled = true;
        const editBtn = btn.previousElementSibling;
        if (editBtn) editBtn.disabled = true;
        
        // Flag/Archive status in UI instead of purging database record
        const statusBadge = row.cells[4].querySelector('.badge');
        if (statusBadge) {
            statusBadge.className = 'badge bg-secondary';
            statusBadge.textContent = 'Archived (Soft Deleted)';
        }
        
        // Update metric
        const eventsMetric = document.getElementById('metric-active-events');
        eventsMetric.textContent = Math.max(0, parseInt(eventsMetric.textContent) - 1);

        showToast("Record archived/soft-deleted successfully.", "bg-secondary");
    }
}

// ==========================================
// SECTION D LOGIC (Nominator Queue)
// ==========================================
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

// ==========================================
// SECTION E LOGIC (Contacts & Exclusion Lists)
// ==========================================
function simulateBulkImport(e) {
    const file = e.target.files[0];
    if (file) {
        // Mock parsing CSV
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

        // Update contacts metric
        const nomMetric = document.getElementById('metric-nominators');
        nomMetric.textContent = parseInt(nomMetric.textContent) + mockContacts.length;

        showToast(`Ingested ${mockContacts.length} contacts successfully from CSV!`);
    }
}

function simulateExclusionUpload(e) {
    const file = e.target.files[0];
    if (file) {
        exclusionEmails.push('uploaded-banned@email.com');
        showToast("Exclusion blocklist updated with uploaded CSV emails.");
    }
}

function testExclusionEmail() {
    const email = document.getElementById('exclusion-test-email').value.trim();
    const alertBox = document.getElementById('exclusion-alert-box');
    
    if (!email) return;

    if (exclusionEmails.includes(email)) {
        alertBox.textContent = "Validation Error: This email is on the exclusion list and cannot be nominated.";
        alertBox.classList.remove('d-none');
    } else {
        alertBox.classList.add('d-none');
        showToast("Email validation clean. Nominator eligible for events.");
    }
}

// ==========================================
// SECTION F LOGIC (MDM Hub)
// ==========================================
function simulateMDMUpload(e) {
    const file = e.target.files[0];
    if (file) {
        const tableBody = document.querySelector('#mdm-log-table tbody');
        
        // Mock unique vs duplicate mapping
        const tr1 = document.createElement('tr');
        tr1.innerHTML = `
            <td>bruce@waynecorp.com</td>
            <td><span class="badge bg-warning text-dark">Duplicate - Ignored</span></td>
        `;
        const tr2 = document.createElement('tr');
        tr2.innerHTML = `
            <td>arthur.dent@galaxy.org</td>
            <td><span class="badge bg-success">Unique - Saved</span></td>
        `;

        tableBody.prepend(tr2);
        tableBody.prepend(tr1);
        showToast("Records ingested. Duplication filter processing complete.");
    }
}

// ==========================================
// SECTION G LOGIC (CMS Customization)
// ==========================================
function saveCMSContent(e) {
    e.preventDefault();
    showToast("CMS content successfully saved and published.");
}

// ==========================================
// SECTION I LOGIC (Domain Boundaries)
// ==========================================
const domainLimits = {
    'Infosys': 50,
    'NeoSoft': 30,
    'Adobe': 15,
    'Cisco': 10
};
const domainActives = {
    'Infosys': 24,
    'NeoSoft': 12,
    'Adobe': 8,
    'Cisco': 4
};

function updateCalculatorLimits() {
    const selectedEnt = document.getElementById('domain-enterprise-name').value;
    const currentLimitInput = document.getElementById('domain-capping-limit');
    
    // Check if input was manually changed by user, otherwise set standard defaults
    if (document.activeElement !== currentLimitInput) {
        currentLimitInput.value = domainLimits[selectedEnt];
    }
    
    domainLimits[selectedEnt] = parseInt(currentLimitInput.value);

    // Update calculator indicators
    document.getElementById('calc-active-state').textContent = domainActives[selectedEnt];
    document.getElementById('calc-cap-limit').textContent = domainLimits[selectedEnt];
}

function calculateAccountBoundaryLimit() {
    const selectedEnt = document.getElementById('domain-enterprise-name').value;
    const activeStateCount = domainActives[selectedEnt];
    const maxCapLimit = domainLimits[selectedEnt];
    const uploadRequestCount = parseInt(document.getElementById('calc-upload-count').value);

    const alertBanner = document.getElementById('calc-alert-banner');
    const successBanner = document.getElementById('calc-success-banner');

    const totalProposed = activeStateCount + uploadRequestCount;
    const vacantSlots = maxCapLimit - activeStateCount;

    if (totalProposed > maxCapLimit) {
        // Halt submission processing and trigger validation warning alert
        successBanner.classList.add('d-none');
        alertBanner.classList.remove('d-none');
        document.getElementById('calc-alert-msg').textContent = `Capped: Only [${vacantSlots >= 0 ? vacantSlots : 0}] vacant nomination slots remain for this account domain.`;
    } else {
        alertBanner.classList.add('d-none');
        successBanner.classList.remove('d-none');
        showToast("Access Allocation Validated!");
    }
}

// Init calculator fields on load
updateCalculatorLimits();
</script>
@endpush
@endsection