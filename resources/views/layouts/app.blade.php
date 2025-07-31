<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'YAKIIN - Teacher Payment System')</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/css/custom.css" rel="stylesheet">
    <style>
    body {
        font-family: 'Poppins', Arial, sans-serif;
        background: #f4f6fa;
    }

    .sidebar {
        min-height: 100vh;
        background-color: #343a40;
        width: 280px;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        z-index: 1050;
        border-radius: 0;
        box-shadow: none;
    }

    .sidebar .logo {
        display: none;
    }

    .sidebar .nav-link {
        color: #fff;
        font-weight: 400;
        border-radius: 6px;
        margin-bottom: 2px;
        background: none;
        box-shadow: none;
    }

    .sidebar .nav-link.active,
    .sidebar .nav-link:hover {
        background-color: #007bff;
        color: #fff;
    }

    .sidebar .sidebar-title {
        font-size: 1.2rem;
        font-weight: 700;
        letter-spacing: 1px;
        color: #fff;
    }

    .sidebar .text-white {
        color: #fff !important;
    }

    .main-content {
        margin-left: 0;
        min-height: 100vh;
        padding-top: 70px;
        background: #f4f6fa;
    }

    @media (min-width: 992px) {
        .sidebar {
            position: fixed;
            width: 280px;
        }

        .main-content {
            margin-left: 280px;
            padding-top: 20px;
        }
    }

    .navbar {
        border-radius: 0 0 16px 16px;
        box-shadow: 0 2px 8px rgba(30, 64, 175, 0.04);
    }

    .navbar .navbar-brand {
        font-weight: 700;
        color: #2563eb !important;
    }

    .navbar .fa-user-circle {
        color: #2563eb;
    }

    .card {
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(30, 64, 175, 0.06);
    }

    .btn-primary,
    .bg-primary {
        background: #2563eb !important;
        border-color: #2563eb !important;
    }

    .btn-primary:hover {
        background: #1e40af !important;
        border-color: #1e40af !important;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.15);
    }

    /* Mobile-first responsive design */
    .sidebar {
        min-height: 100vh;
        background-color: #343a40;
        width: 280px;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        z-index: 1050;
    }

    .sidebar.show {
        transform: translateX(0);
    }

    .sidebar .nav-link {
        color: #fff;
        transition: all 0.3s ease;
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 2px;
    }

    .sidebar .nav-link:hover {
        background-color: #495057;
        color: #fff;
    }

    .sidebar .nav-link.active {
        background-color: #007bff;
    }

    .sidebar .collapse .nav-link {
        font-size: 0.9rem;
        padding-left: 2.5rem;
        padding-top: 8px;
        padding-bottom: 8px;
        border-radius: 6px;
        transition: background 0.2s, color 0.2s;
    }

    .sidebar .collapse .nav-link.active,
    .sidebar .collapse .nav-link:hover {
        background-color: #007bff;
        color: #fff;
    }

    .main-content {
        margin-left: 0;
        min-height: 100vh;
        padding-top: 70px;
        /* Account for mobile header */
    }

    /* Mobile header */
    .mobile-header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1040;
        background: #343a40;
        padding: 12px 16px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1040;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .sidebar-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    /* Desktop styles */
    @media (min-width: 992px) {
        .sidebar {
            position: fixed;
            transform: translateX(0);
            width: 280px;
        }

        .main-content {
            margin-left: 280px;
            padding-top: 20px;
        }

        .mobile-header {
            display: none;
        }

        .sidebar-overlay {
            display: none;
        }
    }

    /* Custom scrollbar for sidebar */
    .sidebar {
        overflow-y: auto;
    }

    .sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: #495057;
        border-radius: 3px;
    }

    /* Mobile optimized cards */
    @media (max-width: 576px) {
        .card {
            margin-bottom: 1rem;
            border-radius: 8px;
        }

        .card-header {
            padding: 12px 16px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .card-body {
            padding: 12px 16px;
        }

        .btn {
            padding: 10px 16px;
            font-size: 0.875rem;
            min-height: 44px;
            font-weight: 500;
        }

        .btn-sm {
            padding: 8px 12px;
            font-size: 0.8rem;
            min-height: 36px;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .modal-dialog {
            margin: 10px;
        }

        .modal-content {
            border-radius: 8px;
        }

        .form-control,
        .form-select {
            min-height: 44px;
            font-size: 16px;
            /* Prevents zoom on iOS */
        }

        .input-group .form-control {
            border-radius: 6px;
        }

        .pagination .page-link {
            padding: 8px 12px;
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .alert {
            border-radius: 6px;
            font-size: 0.9rem;
        }
    }

    /* Tablet optimizations */
    @media (min-width: 577px) and (max-width: 991px) {
        .card {
            border-radius: 8px;
        }

        .btn {
            min-height: 40px;
        }

        .form-control,
        .form-select {
            min-height: 40px;
        }
    }

    /* Touch-friendly improvements */
    .btn-group .btn {
        min-height: 40px;
    }

    @media (max-width: 576px) {
        .btn-group .btn {
            min-height: 44px;
        }
    }

    /* Improved tap targets */
    .nav-link {
        min-height: 44px;
        display: flex;
        align-items: center;
    }

    .dropdown-item {
        min-height: 44px;
        display: flex;
        align-items: center;
        padding: 8px 16px;
    }

    /* Better mobile typography */
    @media (max-width: 576px) {

        .h1,
        .h2,
        .h3,
        .h4,
        .h5,
        .h6 {
            line-height: 1.3;
        }

        .text-sm {
            font-size: 0.875rem;
        }

        .text-xs {
            font-size: 0.8rem;
        }
    }

    /* Improved spacing for mobile */
    @media (max-width: 576px) {
        .mb-4 {
            margin-bottom: 1.5rem !important;
        }

        .mt-4 {
            margin-top: 1.5rem !important;
        }

        .py-4 {
            padding-top: 1.5rem !important;
            padding-bottom: 1.5rem !important;
        }
    }

    /* Loading states */
    .btn.loading {
        opacity: 0.6;
        pointer-events: none;
    }

    .btn.loading::after {
        content: "";
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-left: 8px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    min-width: 44px;
    }

    /* Improved form controls for mobile */
    @media (max-width: 576px) {

        .form-control,
        .form-select {
            font-size: 16px;
            /* Prevents zoom on iOS */
            padding: 12px;
        }

        .input-group .form-control {
            font-size: 16px;
        }
    }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <footer class="bg-light text-start text-dark py-2 fixed-bottom shadow ps-4"
        style="z-index: 1100; left: 280px; width: calc(100% - 280px);">
        <span class="fw-bold">Copyright</span> © 2025 Payroll Guru | <span class="fw-bold">Yayasan YAKIIN</span>
    </footer>
    <!-- Mobile Header -->
    <div class="mobile-header d-lg-none">
        <div class="d-flex justify-content-between align-items-center">
            <!-- HAPUS tombol hamburger di mobile -->
            <h5 class="text-white mb-0">YAKIIN</h5>
            <div class="dropdown">
                <button class="btn btn-link text-white p-0" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle fa-lg"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><span class="dropdown-item-text">{{ Auth::user()->name }}</span></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar p-3 position-fixed" id="sidebar">
            @php
            $foundationSetting = \App\Models\FoundationSetting::first();
            @endphp
            <!-- Sidebar Logo -->
            <div class="text-center mb-4">
                @if($foundationSetting && $foundationSetting->logo_path)
                <img src="{{ asset('storage/' . $foundationSetting->logo_path) }}" alt="Logo Yayasan"
                    class="rounded-circle my-3" style="height:90px;max-width:120px;object-fit:cover;">
                @else
                <img src="/logo.png" alt="Logo Yayasan" class="rounded-circle my-3"
                    style="height:90px;max-width:120px;object-fit:cover;">
                @endif
                <h4 class="text-white mt-2">{{ $foundationSetting->name ?? 'YAKIIN' }}</h4>
                <small class="text-muted">Sistem Absensi & Penggajian Guru</small>

            </div>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                    </a>
                </li>

                @if(auth()->user()->role === 'guru')
                <li class="nav-item">
                    <a class="nav-link{{ request()->is('self-attendance') ? ' active' : '' }}"
                        href="{{ route('self-attendance.index') }}">
                        <i class="fas fa-camera me-2"></i>
                        Absensi Mandiri
                    </a>
                </li>
                @endif

                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'bendahara')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('teachers.index') }}">
                        <i class="fas fa-users me-2"></i>
                        Data Guru
                    </a>
                </li>
                @endif

                @if(auth()->user()->role === 'admin')
                <!-- Admin Only Menu -->
                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center" href="#"
                        data-bs-toggle="collapse" data-bs-target="#managementMenu"
                        aria-expanded="{{ request()->is('shifts*') || request()->is('positions*') || request()->is('allowance-types*') || request()->is('education-levels*') || request()->is('subjects*') ? 'true' : 'false' }}">
                        <span><i class="fas fa-cogs me-2"></i>Manajemen</span>
                        <i class="fas fa-chevron-down float-end ms-auto"></i>
                    </a>
                    <div class="collapse{{ request()->is('shifts*') || request()->is('positions*') || request()->is('allowance-types*') || request()->is('education-levels*') || request()->is('subjects*') ? ' show' : '' }}"
                        id="managementMenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link{{ request()->is('shifts*') ? ' active' : '' }}"
                                    href="{{ route('shifts.index') }}">
                                    <i class="fas fa-clock me-2"></i>
                                    Shift Kerja
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link{{ request()->is('positions*') ? ' active' : '' }}"
                                    href="{{ route('positions.index') }}">
                                    <i class="fas fa-briefcase me-2"></i>
                                    Jabatan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link{{ request()->is('allowance-types*') ? ' active' : '' }}"
                                    href="{{ route('allowance-types.index') }}">
                                    <i class="fas fa-tags me-2"></i>
                                    Jenis Tunjangan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link{{ request()->is('education-levels*') ? ' active' : '' }}"
                                    href="{{ route('education-levels.index') }}">
                                    <i class="fas fa-graduation-cap me-2"></i>
                                    Jenjang Pendidikan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link{{ request()->is('subjects*') ? ' active' : '' }}"
                                    href="{{ route('subjects.index') }}">
                                    <i class="fas fa-book me-2"></i>
                                    Mata Pelajaran
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('attendances.index') }}">
                        <i class="fas fa-calendar-check me-2"></i>
                        Absensi
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('salaries.index') }}">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        Gaji
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('leave-requests.index') }}">
                        <i class="fas fa-calendar-times me-2"></i>
                        @if(auth()->user()->role === 'guru')
                        Pengajuan Cuti
                        @else
                        Manajemen Cuti
                        @endif
                    </a>
                </li>

                <li class="nav-item mt-4">
                    <a class="nav-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
                @if(auth()->user()->role === 'admin')
                <li class="nav-item">
                    <a class="nav-link{{ request()->is('foundation-settings') ? ' active' : '' }}"
                        href="{{ route('foundation-settings.index') }}">
                        <i class="fas fa-cog me-2"></i>
                        Pengaturan Yayasan
                    </a>
                </li>
                @endif
            </ul>
        </nav>

        <!-- Main content -->
        <div class="main-content flex-grow-1 pb-5">
            <!-- Desktop top navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom d-none d-lg-block">
                <div class="container-fluid">
                    <!-- HAPUS tombol hamburger di desktop -->
                    <div class="d-flex align-items-center ms-auto">
                        <span class="me-3">
                            <i class="fas fa-user-circle me-2"></i>
                            {{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})
                        </span>
                    </div>
                </div>
            </nav>

            <!-- Page content -->
            <main class="container-fluid py-3">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sidebar toggle script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Remove collapsible sidebar logic and restore static sidebar
        // Sidebar tetap tampil statis

        // Close sidebar when overlay is clicked
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function() {
                closeSidebar();
            });
        }
        // Close sidebar function
        function closeSidebar() {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
            document.body.style.overflow = '';
        }
        // Close sidebar when clicking on navigation links (mobile)
        const navLinks = sidebar.querySelectorAll('.nav-link:not([data-bs-toggle])');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 992) {
                    closeSidebar();
                }
            });
        });
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992) {
                closeSidebar();
            }
        });
        // Handle device orientation change
        window.addEventListener('orientationchange', function() {
            setTimeout(function() {
                if (window.innerWidth >= 992) {
                    closeSidebar();
                }
            }, 100);
        });

        // Add pull-to-refresh functionality for mobile
        if ('serviceWorker' in navigator) {
            let startY = 0;
            let pullDistance = 0;
            let isPulling = false;

            document.addEventListener('touchstart', function(e) {
                if (window.pageYOffset === 0) {
                    startY = e.touches[0].clientY;
                    isPulling = true;
                }
            });

            document.addEventListener('touchmove', function(e) {
                if (isPulling) {
                    pullDistance = e.touches[0].clientY - startY;
                    if (pullDistance > 0) {
                        e.preventDefault();
                    }
                }
            });

            document.addEventListener('touchend', function() {
                if (isPulling && pullDistance > 100) {
                    window.location.reload();
                }
                isPulling = false;
                pullDistance = 0;
            });
        }

        // Add haptic feedback for mobile interactions
        function addHapticFeedback() {
            if ('vibrate' in navigator) {
                const buttons = document.querySelectorAll('.btn');
                buttons.forEach(button => {
                    button.addEventListener('click', function() {
                        navigator.vibrate(50);
                    });
                });
            }
        }

        // Initialize haptic feedback
        addHapticFeedback();

        // Add loading states to forms
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                const submitButton = form.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.classList.add('loading');
                    submitButton.disabled = true;
                }
            });
        });

        // Auto-dismiss alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                if (alert.classList.contains('show')) {
                    const closeBtn = alert.querySelector('.btn-close');
                    if (closeBtn) {
                        closeBtn.click();
                    }
                }
            }, 5000);
        });
    });
    </script>

    @stack('scripts')
</body>

</html>
