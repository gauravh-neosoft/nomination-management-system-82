<nav class="nav flex-column gap-3">
  <!-- Dashboard Menu Item -->
  <a href="{{ route('nominator-dashboard') }}" class="nav-link {{ Route::currentRouteName() === 'nominator-dashboard' ? 'bg-white text-primary rounded-3' : 'text-white' }} d-flex">
    <i class="bi bi-border-all me-2"></i>
    <span class="fw-semibold fs-09">Dashboard</span>
  </a>
  
  <!-- Events Menu Item (Collapsible Chevron) -->
  <a href="#" class="nav-link text-white d-flex">
    <i class="bi bi-calendar me-2"></i>
    <span class="fw-semibold w-100 fs-09 d-flex justify-content-between">Events<i class="bi bi-chevron-down"></i></span>
  </a>
  
  <!-- Sub-menu list for Events -->
  <ul class="list-style-none list-unstyled ps-4 mb-0">
    <li class="border-left ps-2">
      <a href="{{ route('nominator-active-events') }}" class="nav-link {{ Route::currentRouteName() === 'nominator-active-events' ? 'bg-white text-primary rounded-3' : 'text-white' }} d-flex">
        <span class="fw-semibold fs-09">Active</span>
      </a>
    </li>
    <li class="border-left ps-2">
      <a href="{{ route('nominator-completed-events') }}" class="nav-link {{ Route::currentRouteName() === 'nominator-completed-events' ? 'bg-white text-primary rounded-3' : 'text-white' }} d-flex">
        <span class="fw-semibold fs-09">Completed</span>
      </a>
    </li>
  </ul>
  
  <!-- Nominations Menu Item -->
  <a href="{{ route('nominator-nominations') }}" class="nav-link {{ Route::currentRouteName() === 'nominator-nominations' ? 'bg-white text-primary rounded-3' : 'text-white' }} d-flex">
    <i class="bi bi-people me-2"></i>
    <span class="fw-semibold fs-09">Nominations</span>
  </a>
</nav>