<nav class="nav flex-column gap-2" id="admin-sidebar-menu">
  <!-- Dashboard (Default landing view) -->
  <a href="#dashboard" class="nav-link text-white d-flex align-items-center active-nav-link" data-tab="dashboard">
    <i class="bi bi-border-all me-2"></i>
    <span class="fw-semibold fs-09">Dashboard</span>
  </a>
  
  <!-- Role & Access Management -->
  <a href="#roles" class="nav-link text-white d-flex align-items-center" data-tab="roles">
    <i class="bi bi-shield-lock me-2"></i>
    <span class="fw-semibold fs-09">Role & Access</span>
  </a>
  
  <!-- User Directory -->
  <a href="#users" class="nav-link text-white d-flex align-items-center" data-tab="users">
    <i class="bi bi-person-square me-2"></i>
    <span class="fw-semibold fs-09">User Directory</span>
  </a>
  
  <!-- Event Management -->
  <a href="#events" class="nav-link text-white d-flex align-items-center" data-tab="events">
    <i class="bi bi-calendar-event me-2"></i>
    <span class="fw-semibold fs-09">Event Management</span>
  </a>
  
  <!-- Nominator Queue -->
  <a href="#queue" class="nav-link text-white d-flex align-items-center" data-tab="queue">
    <i class="bi bi-list-stars me-2"></i>
    <span class="fw-semibold fs-09">Nominator Queue</span>
  </a>
  
  <!-- Nominator Contacts -->
  <a href="#contacts" class="nav-link text-white d-flex align-items-center" data-tab="contacts">
    <i class="bi bi-journal-bookmark me-2"></i>
    <span class="fw-semibold fs-09">Nominator Contacts</span>
  </a>
  
  <!-- Exclusion / Blocklists -->
  <a href="#exclusion" class="nav-link text-white d-flex align-items-center" data-tab="exclusion">
    <i class="bi bi-slash-circle me-2"></i>
    <span class="fw-semibold fs-09">Exclusion List</span>
  </a>
  
  <!-- Master Data Hub (MDM) -->
  <a href="#mdm" class="nav-link text-white d-flex align-items-center" data-tab="mdm">
    <i class="bi bi-database me-2"></i>
    <span class="fw-semibold fs-09">Master Data (MDM)</span>
  </a>
  
  <!-- Content Manager (CMS) -->
  <a href="#cms" class="nav-link text-white d-flex align-items-center" data-tab="cms">
    <i class="bi bi-file-earmark-richtext me-2"></i>
    <span class="fw-semibold fs-09">Content Manager</span>
  </a>
  
  <!-- Reports Export -->
  <a href="#reports" class="nav-link text-white d-flex align-items-center" data-tab="reports">
    <i class="bi bi-file-earmark-bar-graph me-2"></i>
    <span class="fw-semibold fs-09">Reports Export</span>
  </a>
  
  <!-- Domain Management -->
  <a href="#domain" class="nav-link text-white d-flex align-items-center" data-tab="domain">
    <i class="bi bi-globe me-2"></i>
    <span class="fw-semibold fs-09">Domain Management</span>
  </a>
</nav>

<style>
#admin-sidebar-menu .nav-link {
  padding: 8px 12px;
  border-radius: 8px;
  transition: all 0.2s ease-in-out;
}
#admin-sidebar-menu .nav-link:hover {
  background-color: rgba(255, 255, 255, 0.1);
}
#admin-sidebar-menu .nav-link.active-nav-link {
  background-color: #ffffff;
  color: var(--bs-primary) !important;
}
</style>
