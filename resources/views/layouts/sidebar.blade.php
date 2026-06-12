<aside
  id="sidebar"
  class="d-lg-block col-12 col-sm-6 col-md-4 col-lg-2 fixed-top position-lg-static bottom-0 z-3 bg-primary sidebar z-2">
  <div class="d-flex flex-column justify-content-between h-100 pb-2">
    <!-- Brand Logo and Mobile Close Button -->
    <div class="d-flex justify-content-between mb-4 ms-3 brand-logo">
      <img
        src="{{ asset('assets/icons/infosys_logo.svg') }}"
        height="30"
        alt="Logo" />

      <button id="sidebar-close-action" class="btn d-lg-none">
        <i class="bi bi-x-circle text-white fs-4"></i>
      </button>
    </div>

    <!-- Top Navigation Menus -->
    <div
      class="ms-3 hide-scrollbar flex-grow-1 overflow-y-scroll mb-4"
      style="height: 200px">
      @php
      $menu = 'nominator-menu';
      $email = auth()->check() && auth()->user() ? strtolower(auth()->user()->email) : '';

      if (str_contains($email, 'unitspoc')) {
          $menu = 'unit-spoc-menu';
      } elseif (str_contains($email, 'eventops')) {
          $menu = 'event-ops-menu';
      } elseif (str_contains($email, 'admin')) {
          $menu = 'admin-menu';
      } else {
          if (auth()->check()) {
              $role = strtolower(auth()->user()->role ?? '');
              if (str_contains($role, 'nominator')) {
                  $menu = 'nominator-menu';
              } elseif (str_contains($role, 'spoc') || str_contains($role, 'unit')) {
                  $menu = 'unit-spoc-menu';
              } elseif (str_contains($role, 'eventops') || str_contains($role, 'event-ops')) {
                  $menu = 'event-ops-menu';
              } elseif (str_contains($role, 'admin')) {
                  $menu = 'admin-menu';
              }
          }
      }

      $path = request()->path();
      if (str_contains($path, 'nominator')) {
          $menu = 'nominator-menu';
      } elseif (str_contains($path, 'unit-spoc') || str_contains($path, 'unit_spoc')) {
          $menu = 'unit-spoc-menu';
      } elseif (str_contains($path, 'event-ops') || str_contains($path, 'event_ops')) {
          $menu = 'event-ops-menu';
      } elseif (str_contains($path, 'admin')) {
          $menu = 'admin-menu';
      }
      @endphp
      @include('layouts.menus.' . $menu)
    </div>

    <!-- Sidebar lower content / User Manual Guide / Logout -->
    <div class="ms-3 d-flex flex-column gap-3">
      <div
        class="d-flex flex-column gap-1 align-items-start rounded-4 bg-secondary bg-opacity-75 p-3"
        style="padding: 7px 14px">
        <p class="text-white fs-6 fw-bold pt-0">
          <i class="bi bi-book me-2"></i> User Manual Guide
        </p>
        <button
          type="button"
          class="btn text-white btn-link p-0 text-decoration-none fw-semibold fs-09">
          View Guide <i class="bi bi-arrow-up-right"></i>
        </button>
      </div>

      <!-- Mobile Profile Action -->
      <button
        class="d-lg-none btn bg-secondary bg-opacity-75 text-white d-flex align-items-center gap-3 px-3 py-2">
        <i class="bi bi-person"></i>

        <div class="d-flex flex-column text-start">
          <span>{{ auth()->user()->name ?? 'Yash Purkar' }}</span>
          @php
          $role = 'Nominator';
          $email = auth()->user() ? auth()->user()->email : '';
          if (str_contains($email, 'unitspoc')) {
          $role = 'Unit SPOC';
          } elseif (str_contains($email, 'eventops')) {
          $role = 'Event OPS';
          } elseif (str_contains($email, 'admin')) {
          $role = 'Admin';
          }
          @endphp
          <small class="text-white-50">{{ $role }}</small>
        </div>
      </button>

      <!-- Logout Action Button -->
      <form action="{{ route('logout') }}" method="POST" id="logout-form" class="d-inline">
        @csrf
        <button
          type="submit"
          class="btn text-white d-flex align-items-center fs-6 border-0 bg-transparent"
          style="padding-left: 15px">
          <i class="bi bi-box-arrow-right me-2"></i>
          <span class="fw-semibold fs-09">Logout</span>
        </button>
      </form>
    </div>
  </div>
</aside>