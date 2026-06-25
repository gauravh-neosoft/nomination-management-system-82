<nav class="nav flex-column gap-3">
  <!-- Dashboard Menu Item -->
  <a href="{{ route('unit-spoc-dashboard') }}" class="nav-link {{ request()->routeIs('unit-spoc-dashboard') ? 'bg-white text-primary rounded-3' : 'text-white' }}">
    <i class="bi bi-border-all me-2"></i>
    <span class="fw-semibold fs-09">Dashboard</span>
  </a>
  
  <!-- Events Menu Item (Collapsible Chevron) -->
  <a href="#" class="nav-link text-white d-flex" data-bs-toggle="collapse" data-bs-target="#eventsSubmenu" aria-expanded="true">
    <i class="bi bi-calendar me-2"></i>
    <span class="fw-semibold w-100 fs-09 d-flex justify-content-between">
      Events <i class="bi bi-chevron-down"></i>
    </span>
  </a>
  
  <!-- Sub-menu list for Events -->
  <ul class="list-style-none list-unstyled ps-4 mb-0 collapse show" id="eventsSubmenu">
    <li class="border-left ps-2">
      <a href="{{ route('unit-spoc-active-events') }}" class="nav-link {{ request()->routeIs('unit-spoc-active-events') ? 'bg-white text-primary rounded-3' : 'text-white' }} pt-0">
        <span class="fw-semibold fs-09">Active</span>
      </a>
    </li>
    <li class="border-left ps-2">
      <a href="{{ route('unit-spoc-completed-events') }}" class="nav-link {{ request()->routeIs('unit-spoc-completed-events') ? 'bg-white text-primary rounded-3' : 'text-white' }}">
        <span class="fw-semibold fs-09">Completed</span>
      </a>
    </li>
  </ul>
  
  <!-- Nominations Menu Item -->
  <a href="{{ route('unit-spoc-nominations') }}" class="nav-link {{ request()->routeIs('unit-spoc-nominations') ? 'bg-white text-primary rounded-3' : 'text-white' }}">
    <i class="bi bi-people me-2"></i>
    <span class="fw-semibold fs-09">Nominations</span>
  </a>
</nav>