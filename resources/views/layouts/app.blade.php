<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Nomination Management System')</title>

    <!-- Bootstrap 5.3.8 -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />

    <!-- Bootstrap Icons -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css"
    />

    <!-- Theme Custom CSS Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/utilities.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/component.css') }}" />

    @stack('styles')
</head>

<body class="bg-primary">
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

    <div class="container-fluid main-wrapper">
        <div class="row h-100">
            <!-- Sidebar Overlay for Mobile -->
            <div
              class="d-lg-none fixed-top bottom-0 bg-black bg-opacity-75 opacity-1 z-1"
              style="visibility: hidden"
              id="sidebar-overlay"
            ></div>
            
            <!-- Sidebar Component -->
            @include('layouts.sidebar')
 
            <!-- Right side wrapper for Header and Main Content -->
            <div class="col-12 col-lg-10 p-0 d-flex flex-column h-100">
                <!-- Top Navbar Header -->
                @include('layouts.header')
 
                <!-- Main Content Panel Area -->
                <div class="flex-grow-1 p-3 pt-0 d-flex overflow-hidden content-scroll">
                    <!-- White scrollable container -->
                    <div class="bg-white rounded-4 w-100 overflow-auto p-4 hide-scrollbar">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
 
    <!-- Global Toast notification helper -->
    <script>
    function showToast(msg, bgClass = 'bg-success') {
        const toastEl = document.getElementById('ajaxToast');
        if (!toastEl) return;
        const toastMsg = document.getElementById('ajaxToastMsg');
        toastEl.className = `toast align-items-center text-white ${bgClass} border-0`;
        toastMsg.textContent = msg;
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }
    </script>

    @stack('scripts')

    <!-- Responsive Mobile Sidebar Menu Toggle Script -->
    <script>
      const sidebar = document.getElementById("sidebar");
      const openSidebar = document.getElementById("sidebar-open-action");
      const closeSidebar = document.getElementById("sidebar-close-action");
      const sidebarOverlay = document.getElementById("sidebar-overlay");

      if (openSidebar && sidebar && sidebarOverlay) {
        openSidebar.addEventListener("click", () => {
          sidebar.style.transform = "translateX(0)";
          sidebarOverlay.style.visibility = "visible";
        });
      }

      function closeSidebarFn() {
        if (sidebar && sidebarOverlay) {
          sidebar.style.transform = "translateX(-100%)";
          sidebarOverlay.style.visibility = "hidden";
        }
      }

      if (closeSidebar) {
        closeSidebar.addEventListener("click", closeSidebarFn);
      }
      if (sidebarOverlay) {
        sidebarOverlay.addEventListener("click", closeSidebarFn);
      }
    </script>
</body>
</html>