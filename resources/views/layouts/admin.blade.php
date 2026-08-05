<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Sistem KKM</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            /* Primary Colors - Hijau Alam & Emerald (sama dengan Flutter) */
            --primary-green: #2E7D32;
            --emerald-green: #00A86B;
            --light-green: #81C784;
            --dark-green: #1B5E20;
            
            /* Neutral Colors */
            --white: #FFFFFF;
            --off-white: #F8F9FA;
            --light-gray: #E0E0E0;
            --gray: #9E9E9E;
            --dark-gray: #424242;
            --black: #212121;
            
            /* Accent Colors - Minangkabau */
            --gold: #D4AF37;
            --maroon: #800020;
            
            /* Status Colors */
            --success: #4CAF50;
            --warning: #FFA726;
            --error: #EF5350;
            --info: #42A5F5;
            
            /* Sidebar */
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --topbar-height: 70px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--off-white);
            color: var(--black);
            overflow-x: hidden;
        }
        
        /* ==================== SIDEBAR ==================== */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--dark-green) 0%, var(--primary-green) 100%);
            color: var(--white);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }
        
        /* Sidebar Header */
        .sidebar-header {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s;
        }
        
        .logo-icon {
            width: 40px;
            height: 40px;
            background: var(--gold);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-green);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        }
        
        .logo-icon i {
            font-size: 20px;
        }
        
        .logo-text {
            font-size: 20px;
            font-weight: 700;
            white-space: nowrap;
            letter-spacing: -0.5px;
        }
        
        .logo-text .text-gold {
            color: var(--gold);
        }
        
        .sidebar.collapsed .logo-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        
        .toggle-btn {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: var(--white);
            width: 36px;
            height: 36px;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        
        .toggle-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }
        
        .sidebar.collapsed .toggle-btn {
            transform: rotate(180deg);
        }
        
        /* Sidebar Menu */
        .sidebar-menu {
            flex: 1;
            padding: 24px 12px;
            overflow-y: auto;
            overflow-x: hidden;
        }
        
        .sidebar-menu::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar-menu::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }
        
        .menu-section {
            margin-bottom: 28px;
        }
        
        .menu-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.5);
            padding: 0 16px;
            margin-bottom: 12px;
            transition: all 0.3s;
        }
        
        .sidebar.collapsed .menu-title {
            opacity: 0;
            height: 0;
            margin: 0;
            overflow: hidden;
        }
        
        .menu-item {
            position: relative;
            margin-bottom: 6px;
        }
        
        .menu-link {
            display: flex;
            align-items: center;
            padding: 14px 16px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.2s;
            font-weight: 500;
            font-size: 14px;
            gap: 14px;
        }
        
        .menu-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--white);
            transform: translateX(4px);
        }
        
        .menu-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: var(--white);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .menu-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: var(--gold);
            border-radius: 0 4px 4px 0;
        }
        
        .menu-icon {
            width: 20px;
            text-align: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        
        .menu-text {
            flex: 1;
            white-space: nowrap;
            transition: all 0.3s;
        }
        
        .sidebar.collapsed .menu-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        
        .menu-badge {
            background: var(--error);
            color: var(--white);
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 20px;
            min-width: 22px;
            text-align: center;
            transition: all 0.3s;
        }
        
        .sidebar.collapsed .menu-badge {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        
        /* Sidebar Footer */
        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.1);
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.05);
            transition: all 0.2s;
            cursor: pointer;
        }
        
        .user-profile:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: var(--gold);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            color: var(--dark-green);
            flex-shrink: 0;
        }
        
        .user-info {
            flex: 1;
            min-width: 0;
            transition: all 0.3s;
        }
        
        .sidebar.collapsed .user-info {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 14px;
            color: var(--white);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .user-role {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
        }
        
        /* ==================== MAIN CONTENT ==================== */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar.collapsed ~ .main-wrapper {
            margin-left: var(--sidebar-collapsed-width);
        }
        
        /* Topbar */
        .topbar {
            height: var(--topbar-height);
            background: var(--white);
            border-bottom: 1px solid var(--light-gray);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .topbar-left h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--black);
        }
        
        .breadcrumb {
            display: flex;
            gap: 8px;
            margin-top: 4px;
            font-size: 13px;
            color: var(--gray);
        }
        
        .breadcrumb a {
            color: var(--primary-green);
            text-decoration: none;
        }
        
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .topbar-btn {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            border: none;
            background: var(--off-white);
            color: var(--dark-gray);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            position: relative;
        }
        
        .topbar-btn:hover {
            background: var(--light-gray);
            transform: translateY(-2px);
        }
        
        .topbar-btn .badge {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            background: var(--error);
            border-radius: 50%;
            border: 2px solid var(--white);
        }
        
        /* Content Area */
        .content {
            flex: 1;
            padding: 32px;
        }
        
        /* Cards */
        .card {
            background: var(--white);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            margin-bottom: 24px;
        }
        
        .card-header {
            font-size: 18px;
            font-weight: 700;
            color: var(--black);
            margin-bottom: 20px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            
            .main-wrapper {
                margin-left: 0;
            }
            
            .topbar {
                padding: 0 16px;
            }
            
            .content {
                padding: 20px 16px;
            }
        }
        
        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .content > * {
            animation: slideIn 0.4s ease-out;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <div class="logo-icon">
                    <img src="{{ asset('assets/images/laporpadang.png') }}" alt="Sistem KKM" style="width: 28px; height: 28px; object-fit: contain;">
                </div>
                <div class="logo-text">
                    Sistem <span class="text-gold">KKM</span>
                </div>
            </div>
            <button class="toggle-btn" onclick="toggleSidebar()">
                <i class="fas fa-angles-left"></i>
            </button>
        </div>
        
        <!-- Sidebar Menu -->
        <nav class="sidebar-menu">
            <!-- Main Menu -->
            <div class="menu-section">
                <div class="menu-title">Menu Utama</div>
                
                <div class="menu-item">
                    <a href="{{ route('admin.dashboard') }}" class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="menu-icon fas fa-chart-line"></i>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </div>
                
                <div class="menu-item">
                    <a href="{{ route('admin.reports.index') }}" class="menu-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <i class="menu-icon fas fa-file-alt"></i>
                        <span class="menu-text">Laporan</span>
                        @if(isset($pendingCount) && $pendingCount > 0)
                            <span class="menu-badge">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </div>
                
                <div class="menu-item">
                    <a href="{{ route('admin.users.index') }}" class="menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="menu-icon fas fa-users"></i>
                        <span class="menu-text">Pengguna</span>
                    </a>
                </div>
                
                <div class="menu-item">
                    <a href="{{ route('admin.categories.index') }}" class="menu-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="menu-icon fas fa-folder-open"></i>
                        <span class="menu-text">Kategori</span>
                    </a>
                </div>
            </div>
            
            <!-- Analytics -->
            <div class="menu-section">
                <div class="menu-title">Analitik</div>
                
                <div class="menu-item">
                    <a href="{{ route('admin.analytics.overview') }}" class="menu-link {{ request()->routeIs('admin.analytics.overview') ? 'active' : '' }}">
                        <i class="menu-icon fas fa-chart-pie"></i>
                        <span class="menu-text">Overview</span>
                    </a>
                </div>
                
                <div class="menu-item">
                    <a href="{{ route('admin.analytics.districts') }}" class="menu-link {{ request()->routeIs('admin.analytics.districts') ? 'active' : '' }}">
                        <i class="menu-icon fas fa-map-marked-alt"></i>
                        <span class="menu-text">Per Kecamatan</span>
                    </a>
                </div>
            </div>
            
            <!-- Settings -->
            <div class="menu-section">
                <div class="menu-title">Pengaturan</div>
                
                <div class="menu-item">
                    <a href="{{ route('admin.settings.general') }}" class="menu-link {{ request()->routeIs('admin.settings.general') ? 'active' : '' }}">
                        <i class="menu-icon fas fa-cog"></i>
                        <span class="menu-text">Umum</span>
                    </a>
                </div>
                
                <div class="menu-item">
                    <a href="{{ route('admin.activity-logs') }}" class="menu-link {{ request()->routeIs('admin.activity-logs') ? 'active' : '' }}">
                        <i class="menu-icon fas fa-history"></i>
                        <span class="menu-text">Log Aktivitas</span>
                    </a>
                </div>
            </div>
        </nav>
        
        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <div class="user-profile" onclick="toggleUserMenu()">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <div class="user-role">Administrator</div>
                </div>
            </div>
        </div>
    </aside>
    
    <!-- Main Content -->
    <div class="main-wrapper">
        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-left">
                <h1>@yield('page-title', 'Dashboard')</h1>
                <div class="breadcrumb">
                    @yield('breadcrumb')
                </div>
            </div>
            
            <div class="topbar-right">
                <button class="topbar-btn" title="Notifikasi">
                    <i class="fas fa-bell"></i>
                    <span class="badge"></span>
                </button>
                
                <button class="topbar-btn" title="Pengaturan">
                    <i class="fas fa-cog"></i>
                </button>
                
                <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="topbar-btn" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </header>
        
        <!-- Page Content -->
        <main class="content">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif
            
            @yield('content')
        </main>
    </div>
    
    <!-- Scripts -->
    <script>
        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        }
        
        // Load saved sidebar state
        document.addEventListener('DOMContentLoaded', () => {
            const collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (collapsed) {
                document.getElementById('sidebar').classList.add('collapsed');
            }
        });
        
        // Toggle User Menu (placeholder - implement dropdown if needed)
        function toggleUserMenu() {
            // Implement user dropdown menu here
            console.log('User menu clicked');
        }
        
        // Mobile menu toggle
        function toggleMobileMenu() {
            document.getElementById('sidebar').classList.toggle('mobile-open');
        }
    </script>
    
    @stack('scripts')
</body>
</html>
