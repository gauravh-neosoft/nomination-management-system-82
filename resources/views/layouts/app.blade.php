<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

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