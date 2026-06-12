<header class="top-header">

    <div class="page-title">
        Nomination Management System
    </div>

    <div class="header-actions">

        <button class="icon-btn">
            <i class="bi bi-search"></i>
        </button>

        <button class="icon-btn position-relative">
            <i class="bi bi-bell"></i>
            <span class="notification-badge"></span>
        </button>

        <div class="user-info">

            <div class="avatar">
                <i class="bi bi-person-circle"></i>
            </div>

            <div class="user-details">
                <h6 class="mb-0 text-white font-semibold">{{ auth()->user()->name ?? 'Yash Purkar' }}</h6>
                <small class="user-role">{{ auth()->user()->role ?? 'Nominator' }}</small>
            </div>

        </div>

    </div>

</header>