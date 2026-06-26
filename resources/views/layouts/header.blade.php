<header class="navbar pt-0 px-4 flex-shrink-0">
  <div class="d-flex align-items-center">
    <!-- Mobile Sidebar Open Menu Button -->
    <button class="btn d-lg-none" id="sidebar-open-action">
      <i
        class="bi bi-list text-white fs-4 position-relative"
        style="top: 10px"></i>
    </button>

    <!-- Application Logo Text -->
    <h1 class="navbar-brand text-white mb-1 pt-0 nav-heading">
      Nomination Management System
    </h1>
  </div>

  <!-- Header Actions (Search, Notification, Profile) -->
  <div
    class="d-flex align-items-center gap-3"
    style="margin-top: 12px">
    <button class="btn text-white p-0">
      <i class="bi bi-search icon"></i>
    </button>

    <!-- Notifications Dropdown -->
    <div class="dropdown">
      <button
        class="btn text-white p-0 position-relative border-0"
        data-bs-toggle="dropdown">
        <i class="bi bi-bell icon"></i>
        <span
          class="bg-danger position-absolute rounded-circle bell-badge"></span>
      </button>

      @include('layouts.notifications')
    </div>

    <!-- Profile Button visible on Desktop Screens -->
    <a
      href="{{ route('profile') }}"
      class="d-none d-lg-flex btn bg-secondary bg-opacity-75 text-white align-items-center gap-3 px-3 py-2 text-decoration-none">
      <i class="bi bi-person"></i>

      <div class="d-flex flex-column text-start">
        @php
        $role = 'Nominator';
        $email = auth()->user() ? auth()->user()->email : '';
        if (str_contains($email, 'unit_spoc') || str_contains($email, 'unitspoc')) {
        $role = 'Unit SPOC';
        } elseif (str_contains($email, 'event_ops') || str_contains($email, 'eventops')) {
        $role = 'Event OPS';
        } elseif (str_contains($email, 'admin')) {
        $role = 'Admin';
        }
        @endphp
        <span class="text-lg fw-bold">{{ auth()->user()->name ?? 'Yash Purkar' }} <small class="text-white-50">({{ $role }})</small></span>
      </div>
    </a>
  </div>
</header>