<nav class="nav flex-column gap-2" id="admin-sidebar-menu">
  <!-- Dashboard (Default landing view) -->
  <a href="{{ route('admin-dashboard') }}" class="nav-link text-white d-flex align-items-center {{ Route::currentRouteName() === 'admin-dashboard' ? 'active-nav-link' : '' }}">
    <i class="bi bi-border-all me-2"></i>
    <span class="fw-semibold fs-09">Dashboard</span>
  </a>
  
  <!-- Role & Access Management -->
  <a href="{{ route('admin-roles') }}" class="nav-link text-white d-flex align-items-center {{ Route::currentRouteName() === 'admin-roles' ? 'active-nav-link' : '' }}">
    <i class="bi bi-shield-lock me-2"></i>
    <span class="fw-semibold fs-09">Role & Access</span>
  </a>
  
  <!-- User Directory -->
  <a href="{{ route('admin-users') }}" class="nav-link text-white d-flex align-items-center {{ Route::currentRouteName() === 'admin-users' ? 'active-nav-link' : '' }}">
    <i class="bi bi-person-square me-2"></i>
    <span class="fw-semibold fs-09">User Directory</span>
  </a>
  
  <!-- Event Management -->
  <a href="{{ route('admin-events') }}" class="nav-link text-white d-flex align-items-center {{ Route::currentRouteName() === 'admin-events' || Route::currentRouteName() === 'admin-new-event-form' ? 'active-nav-link' : '' }}">
    <i class="bi bi-calendar-event me-2"></i>
    <span class="fw-semibold fs-09">Event Management</span>
  </a>
  
  <!-- Nominator Queue -->
  <a href="{{ route('admin-queue') }}" class="nav-link text-white d-flex align-items-center {{ Route::currentRouteName() === 'admin-queue' ? 'active-nav-link' : '' }}">
    <i class="bi bi-list-stars me-2"></i>
    <span class="fw-semibold fs-09">Nominator Queue</span>
  </a>
  
  <!-- Nominator Contacts -->
  <a href="{{ route('admin-contacts') }}" class="nav-link text-white d-flex align-items-center {{ Route::currentRouteName() === 'admin-contacts' ? 'active-nav-link' : '' }}">
    <i class="bi bi-journal-bookmark me-2"></i>
    <span class="fw-semibold fs-09">Nominator Contacts</span>
  </a>
  
  <!-- Exclusion / Blocklists -->
  <a href="{{ route('admin-exclusion') }}" class="nav-link text-white d-flex align-items-center {{ Route::currentRouteName() === 'admin-exclusion' ? 'active-nav-link' : '' }}">
    <i class="bi bi-slash-circle me-2"></i>
    <span class="fw-semibold fs-09">Exclusion List</span>
  </a>
  
  <!-- Master Data Hub (MDM) -->
  <a href="{{ route('admin-mdm') }}" class="nav-link text-white d-flex align-items-center {{ Route::currentRouteName() === 'admin-mdm' ? 'active-nav-link' : '' }}">
    <i class="bi bi-database me-2"></i>
    <span class="fw-semibold fs-09">Master Data (MDM)</span>
  </a>
  
  <!-- Dropdown Management -->
  <a href="{{ route('admin-dropdown') }}" class="nav-link text-white d-flex align-items-center {{ Route::currentRouteName() === 'admin-dropdown' ? 'active-nav-link' : '' }}">
    <i class="bi bi-list-ul me-2"></i>
    <span class="fw-semibold fs-09">Dropdown Management</span>
  </a>
  
  <!-- Content Manager (CMS) -->
  <a href="{{ route('admin-cms') }}" class="nav-link text-white d-flex align-items-center {{ Route::currentRouteName() === 'admin-cms' ? 'active-nav-link' : '' }}">
    <i class="bi bi-file-earmark-richtext me-2"></i>
    <span class="fw-semibold fs-09">Content Manager</span>
  </a>
  
  <!-- Reports Export -->
  <a href="{{ route('admin-reports') }}" class="nav-link text-white d-flex align-items-center {{ Route::currentRouteName() === 'admin-reports' ? 'active-nav-link' : '' }}">
    <i class="bi bi-file-earmark-bar-graph me-2"></i>
    <span class="fw-semibold fs-09">Reports Export</span>
  </a>
  
  <!-- Domain Management -->
  <a href="{{ route('admin-domain') }}" class="nav-link text-white d-flex align-items-center {{ Route::currentRouteName() === 'admin-domain' ? 'active-nav-link' : '' }}">
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
