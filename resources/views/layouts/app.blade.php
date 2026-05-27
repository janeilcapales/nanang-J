<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Management</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-bg: #1a1008;
            --sidebar-width: 260px;
            --accent: #F97316;
            --accent-gold: #F59E0B;
            --accent-hover: #EA580C;
            --text-light: #FFF7ED;
            --text-muted: #a89070;
            --content-bg: #faf8f5;
            --card-bg: #ffffff;
            --border: #e8e0d5;
            --shadow: 0 4px 24px rgba(0,0,0,0.08);
            --radius: 14px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--content-bg);
            color: #2d1f0f;
            display: flex;
            min-height: 100vh;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
            box-shadow: 4px 0 30px rgba(0,0,0,0.25);
        }

        .sidebar-brand {
            padding: 32px 28px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }

        .sidebar-brand .logo-icon {
            width: 42px; height: 42px;
            background: var(--accent);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            margin-bottom: 12px;
        }

        .sidebar-brand h1 {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: var(--text-light);
            line-height: 1.2;
        }

        .sidebar-brand p {
            font-size: 11px;
            color: var(--text-muted);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-top: 3px;
        }

        .sidebar-section-label {
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 20px 28px 8px;
            font-weight: 500;
        }

        .nav-link-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 28px;
            color: #c4a882;
            text-decoration: none;
            font-size: 14px;
            font-weight: 400;
            border-radius: 0;
            transition: all 0.2s ease;
            position: relative;
        }

        .nav-link-item i {
            width: 18px;
            text-align: center;
            font-size: 15px;
        }

        .nav-link-item:hover {
            color: var(--text-light);
            background: rgba(249, 115, 22, 0.12);
            text-decoration: none;
        }

        .nav-link-item.active {
            color: var(--accent);
            background: rgba(249, 115, 22, 0.15);
        }

        .nav-link-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 3px;
            background: var(--accent);
            border-radius: 0 3px 3px 0;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 20px 28px;
            border-top: 1px solid rgba(255,255,255,0.07);
        }

        .user-badge {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--accent), var(--accent-gold));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
            color: white;
            font-weight: 600;
        }

        .user-info span {
            display: block;
            font-size: 13px;
            color: var(--text-light);
            font-weight: 500;
        }

        .user-info small {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: capitalize;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 14px;
            padding: 9px 16px;
            background: rgba(239, 68, 68, 0.12);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 8px;
            font-size: 13px;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            width: 100%;
            text-align: left;
            transition: all 0.2s;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
        }

        /* ── MAIN CONTENT ── */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: white;
            padding: 16px 36px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-title {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            color: #1a1008;
            font-weight: 700;
        }

        .topbar-date {
            font-size: 13px;
            color: #888;
        }

        .page-body {
            padding: 32px 36px;
            flex: 1;
        }

        /* ── CARDS ── */
        .stat-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            padding: 24px 28px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent), var(--accent-gold));
        }

        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            margin-bottom: 16px;
        }

        .stat-icon.orange { background: #FFF3E8; color: var(--accent); }
        .stat-icon.gold   { background: #FFFBEB; color: var(--accent-gold); }
        .stat-icon.green  { background: #F0FDF4; color: #22c55e; }
        .stat-icon.blue   { background: #EFF6FF; color: #3b82f6; }

        .stat-number {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            font-weight: 700;
            color: #1a1008;
            line-height: 1;
        }

        .stat-label {
            font-size: 13px;
            color: #888;
            margin-top: 6px;
            font-weight: 400;
        }

        /* ── DISH CARDS ── */
        .dish-card {
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .dish-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 36px rgba(0,0,0,0.13);
        }

        .dish-card-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            background: #f5ede0;
            display: flex; align-items: center; justify-content: center;
            font-size: 48px;
            color: #d4b896;
        }

        .dish-card-body {
            padding: 16px 18px;
        }

        .dish-card-name {
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            font-weight: 600;
            color: #1a1008;
        }

        .dish-card-price {
            font-size: 15px;
            color: var(--accent);
            font-weight: 600;
            margin-top: 4px;
        }

        /* ── FORMS ── */
        .form-card {
            background: white;
            border-radius: var(--radius);
            padding: 32px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            max-width: 680px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 500;
            color: #555;
            margin-bottom: 6px;
            display: block;
        }

        .form-control {
            border: 1.5px solid #e0d5c8;
            border-radius: 9px;
            padding: 11px 14px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.12);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%23888' d='M6 8L0 0h12z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 36px;
        }

        /* ── BUTTONS ── */
        .btn-primary {
            background: var(--accent);
            border: none;
            border-radius: 9px;
            padding: 11px 24px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: white;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(249, 115, 22, 0.35);
        }

        .btn-warning {
            background: var(--accent-gold);
            border: none;
            border-radius: 9px;
            padding: 11px 24px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: white;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-warning:hover {
            background: #D97706;
            color: white;
            transform: translateY(-1px);
        }

        .btn-danger {
            background: #ef4444;
            border: none;
            border-radius: 7px;
            padding: 7px 14px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            color: white;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-danger:hover { background: #dc2626; }

        .btn-sm-edit {
            background: #EFF6FF;
            color: #3b82f6;
            border: 1px solid #BFDBFE;
            border-radius: 7px;
            padding: 7px 14px;
            font-size: 13px;
            font-family: 'DM Sans', sans-serif;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
        }

        .btn-sm-edit:hover {
            background: #DBEAFE;
            color: #2563eb;
        }

        /* ── TABLES ── */
        .data-table {
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            width: 100%;
        }

        .data-table thead th {
            background: #1a1008;
            color: #c4a882;
            padding: 14px 18px;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
            border: none;
        }

        .data-table tbody td {
            padding: 14px 18px;
            font-size: 14px;
            border-bottom: 1px solid #f0e8dc;
            vertical-align: middle;
        }

        .data-table tbody tr:hover { background: #fffaf5; }
        .data-table tbody tr:last-child td { border-bottom: none; }

        /* ── PAGE HEADERS ── */
        .page-header {
            margin-bottom: 28px;
        }

        .page-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: #1a1008;
            font-weight: 700;
        }

        .page-header p {
            color: #888;
            font-size: 14px;
            margin-top: 4px;
        }

        /* ── ALERTS ── */
        .alert-suggestion {
            background: linear-gradient(135deg, #FFF7ED, #FFFBEB);
            border: 1px solid #FED7AA;
            border-radius: 12px;
            padding: 18px 22px;
            margin-top: 20px;
        }

        .alert-suggestion strong { color: var(--accent); }

        /* ── FORM GROUP SPACING ── */
        .form-group { margin-bottom: 20px; }

        /* ── BADGE ── */
        .role-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
            background: rgba(249, 115, 22, 0.12);
            color: var(--accent);
        }

        /* Section divider */
        .section-divider {
            height: 1px;
            background: var(--border);
            margin: 28px 0;
        }

        /* Toast-style badge in topbar */
        .topbar-badge {
            background: linear-gradient(135deg, var(--accent), var(--accent-gold));
            color: white;
            border-radius: 20px;
            padding: 5px 14px;
            font-size: 12px;
            font-weight: 500;
        }
    </style>
</head>
<body>

<!-- ══ SIDEBAR ══ -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="logo-icon">🍽️</div>
        <h1>La Mesa</h1>
        <p>Restaurant System</p>
    </div>

    @auth
        @if(Auth::user()->role === 'admin')
            <div class="sidebar-section-label">Management</div>
            <a href="{{ route('dishes.index') }}" class="nav-link-item {{ request()->routeIs('dishes.*') ? 'active' : '' }}">
                <i class="fa-solid fa-utensils"></i> Manage Dishes
            </a>
            <a href="{{ route('reservations.index') }}" class="nav-link-item {{ request()->routeIs('reservations.*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-check"></i> Reservations
            </a>
            <a href="{{ route('transactions.index') }}" class="nav-link-item {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                <i class="fa-solid fa-receipt"></i> Transactions
            </a>

            <div class="sidebar-section-label">Analytics</div>
            <a href="{{ route('reports.index') }}" class="nav-link-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-bar"></i> Reports
            </a>
            <a href="{{ route('ai.index') }}" class="nav-link-item {{ request()->routeIs('ai.*') ? 'active' : '' }}">
                <i class="fa-solid fa-wand-magic-sparkles"></i> AI Suggestion
            </a>

        @elseif(Auth::user()->role === 'customer')
            <div class="sidebar-section-label">My Account</div>
            <a href="{{ route('reservations.index') }}" class="nav-link-item {{ request()->routeIs('reservations.*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-check"></i> My Reservations
            </a>
            <a href="{{ route('transactions.index') }}" class="nav-link-item {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                <i class="fa-solid fa-receipt"></i> My Transactions
            </a>
        @endif
    @endauth

    <div class="sidebar-footer">
        @auth
        <div class="user-badge">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div class="user-info">
                <span>{{ Auth::user()->name }}</span>
                <small class="role-badge">{{ Auth::user()->role }}</small>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
            </button>
        </form>
        @endauth
    </div>
</aside>

<!-- ══ MAIN CONTENT ══ -->
<div class="main-content">
    <div class="topbar">
        <div class="topbar-title">
            @yield('page-title', 'Dashboard')
        </div>
        <div class="topbar-date">
            <span class="topbar-badge">🌟 {{ now()->format('l, F j, Y') }}</span>
        </div>
    </div>

    <div class="page-body">
        @yield('content')
    </div>
</div>

</body>
</html>