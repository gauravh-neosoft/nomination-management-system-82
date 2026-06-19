<nav class="nav flex-column gap-3" id="event-ops-sidebar-menu">
  <!-- Dashboard Menu Item -->
  <a href="{{ route('event-ops-dashboard') }}" class="nav-link {{ Route::currentRouteName() === 'event-ops-dashboard' ? 'bg-white text-primary rounded-3' : 'text-white' }} d-flex align-items-center">
    <i class="bi bi-border-all me-2"></i>
    <span class="fw-semibold fs-09">Dashboard</span>
  </a>
  
  <!-- Events Menu Item (Collapsible Chevron) -->
  @php
    $isEventsActive = in_array(Route::currentRouteName(), ['event-ops-active-events', 'event-ops-completed-events']);
  @endphp
  <a href="#eventsSubmenu" data-bs-toggle="collapse" class="nav-link text-white d-flex align-items-center justify-content-between" aria-expanded="{{ $isEventsActive ? 'true' : 'false' }}">
    <span class="d-flex align-items-center">
      <i class="bi bi-calendar me-2"></i>
      <span class="fw-semibold fs-09">Events</span>
    </span>
    <i class="bi bi-chevron-down ms-auto fs-08"></i>
  </a>
  
  <!-- Sub-menu list for Events -->
  <div class="collapse {{ $isEventsActive ? 'show' : '' }}" id="eventsSubmenu">
    <ul class="list-style-none list-unstyled ps-4 mb-0 d-flex flex-column">
      <li class="border-left ps-2">
        <a href="{{ route('event-ops-active-events') }}" class="nav-link {{ Route::currentRouteName() === 'event-ops-active-events' ? 'bg-white text-primary rounded-3' : 'text-white' }} py-1 d-flex">
          <span class="fw-semibold fs-09">Active</span>
        </a>
      </li>
      <li class="border-left ps-2">
        <a href="{{ route('event-ops-completed-events') }}" class="nav-link {{ Route::currentRouteName() === 'event-ops-completed-events' ? 'bg-white text-primary rounded-3' : 'text-white' }} py-1 d-flex">
          <span class="fw-semibold fs-09">Completed</span>
        </a>
      </li>
    </ul>
  </div>
  
  <!-- Nominations Menu Item -->
  <a href="{{ route('event-ops-nominations') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['event-ops-nominations', 'event-ops-event-nominations']) ? 'bg-white text-primary rounded-3' : 'text-white' }} d-flex align-items-center">
    <i class="bi bi-people me-2"></i>
    <span class="fw-semibold fs-09">Nominations</span>
  </a>

  <!-- Reports Menu Item -->
  <a href="{{ route('event-ops-reports') }}" class="nav-link {{ Route::currentRouteName() === 'event-ops-reports' ? 'bg-white text-primary rounded-3' : 'text-white' }} d-flex align-items-center">
    <i class="bi bi-file-earmark-text me-2"></i>
    <span class="fw-semibold fs-09">Reports</span>
  </a>

  <!-- DNC Contact Menu Item -->
  <a href="{{ route('event-ops-dnc-contact') }}" class="nav-link {{ Route::currentRouteName() === 'event-ops-dnc-contact' ? 'bg-white text-primary rounded-3' : 'text-white' }} d-flex align-items-center">
    <i class="bi bi-file-person me-2"></i>
    <span class="fw-semibold fs-09">DNC Contact</span>
  </a>
</nav>

<style>
#event-ops-sidebar-menu .nav-link {
  transition: all 0.2s ease-in-out;
}
#event-ops-sidebar-menu .nav-link .bi-chevron-down {
  transition: transform 0.2s ease;
}
#event-ops-sidebar-menu .nav-link[aria-expanded="true"] .bi-chevron-down {
  transform: rotate(180deg);
}
</style>