<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Guitarclassbynde</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome 6.5.1 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Phosphor Icons -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/duotone/style.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css" />

    <style>
        :root {
            --adm-font: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --adm-bg: #F8FAFC;
            --adm-sidebar-bg: #FFFFFF;
            --adm-border: #E2E8F0;
            --adm-text: #0F172A;
            --adm-text-muted: #64748B;
            --adm-primary: #2563EB;
            --adm-primary-hover: #1D4ED8;
            --adm-primary-light: #EFF6FF;
            --adm-radius: 14px;
            --adm-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05);
        }

        body.admin-shell {
            font-family: var(--adm-font);
            background-color: var(--adm-bg);
            color: var(--adm-text);
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .admin-app {
            display: flex;
            min-height: 100vh;
        }

        .admin-sidebar {
            width: 260px;
            background: var(--adm-sidebar-bg);
            border-right: 1px solid var(--adm-border);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1.25rem 1rem;
            position: sticky;
            top: 0;
            height: 100vh;
            z-index: 1020;
            flex-shrink: 0;
        }

        .admin-sidebar .brand {
            display: flex;
            align-items: center;
            gap: .75rem;
            text-decoration: none;
            color: #0F172A;
            font-weight: 800;
            letter-spacing: -.02em;
            margin-bottom: 1.25rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #F1F5F9;
        }

        .admin-shell .brand-badge {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .admin-shell .menu-label {
            font-size: .7rem;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #94A3B8;
            margin: .95rem .65rem .45rem;
            font-weight: 800;
        }

        .admin-shell .side-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .65rem;
            padding: .65rem .85rem;
            border-radius: 12px;
            text-decoration: none;
            color: #475569;
            font-size: .9rem;
            font-weight: 600;
            transition: all .2s ease;
            margin-bottom: .25rem;
        }

        .admin-shell .side-link:hover {
            background: #F1F5F9;
            color: #0F172A;
            transform: translateX(2px);
        }

        .admin-shell .side-link:hover i {
            color: #0F172A !important;
        }

        .admin-shell .side-link.active {
            color: #2563EB;
            background: #EFF6FF;
            border: 1px solid rgba(37, 99, 235, 0.2);
            font-weight: 700;
        }

        .admin-shell .side-link i {
            font-size: 1rem;
            color: #475569 !important;
            transition: color 0.2s ease;
        }

        .admin-shell .side-link.active i {
            color: #2563EB !important;
        }

        .admin-shell .sidebar-footer {
            padding-top: 1rem;
            border-top: 1px solid #F1F5F9;
        }

        .admin-shell .status-box {
            padding: .75rem;
            border-radius: 12px;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .admin-shell .admin-main {
            min-width: 0;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .admin-shell .admin-topbar {
            position: sticky;
            top: 0;
            z-index: 1010;
            background: rgba(255, 255, 255, 0.92) !important;
            border-bottom: 1px solid #E2E8F0 !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .admin-shell .topbar-inner {
            display: flex;
            align-items: center;
            gap: .8rem;
            padding: .85rem 1.5rem;
        }

        .admin-shell .side-toggle {
            border: 1px solid #E2E8F0;
            background: #ffffff;
            color: #1E293B;
            border-radius: 10px;
            width: 40px;
            height: 40px;
            display: none;
            align-items: center;
            justify-content: center;
            box-shadow: var(--adm-shadow);
        }

        .admin-shell .top-search {
            flex: 1;
            position: relative;
            max-width: 540px;
        }

        .admin-shell .top-search input {
            width: 100%;
            border: 1px solid #CBD5E1 !important;
            background: #F8FAFC;
            border-radius: 12px;
            height: 40px;
            padding-left: 2.8rem !important;
            padding-right: 3.2rem !important;
            color: #0F172A;
            font-size: .85rem;
        }

        .admin-shell .top-search input:focus {
            background: #ffffff;
            border-color: #2563EB !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .admin-shell .top-search i {
            position: absolute;
            left: 1rem !important;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            font-size: .9rem;
            pointer-events: none;
            z-index: 5;
        }

        .admin-shell .top-search kbd {
            position: absolute;
            right: .75rem;
            top: 50%;
            transform: translateY(-50%);
            background: #ffffff;
            color: #64748B;
            border: 1px solid #CBD5E1;
            font-size: .68rem;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
        }

        .admin-shell .top-search-results {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            box-shadow: 0 16px 42px rgba(15, 23, 42, .12);
            overflow: hidden;
            z-index: 1050;
            display: none;
            max-height: 300px;
        }

        .admin-shell .top-search-results.show {
            display: block;
        }

        .admin-shell .top-search-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .65rem .9rem;
            color: #1E293B;
            text-decoration: none;
            font-size: .84rem;
            font-weight: 600;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
            border-bottom: 1px solid #F1F5F9;
        }

        .admin-shell .top-search-item:last-child {
            border-bottom: none;
        }

        .admin-shell .top-search-item:hover,
        .admin-shell .top-search-item.active {
            background: #EFF6FF;
            color: #2563EB;
        }

        .admin-shell .top-search-empty {
            padding: .85rem;
            color: #64748B;
            font-size: .82rem;
            text-align: center;
        }

        .admin-shell .top-actions {
            display: flex;
            align-items: center;
            gap: .65rem;
            margin-left: auto;
        }

        .admin-shell .btn-lms {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .5rem .85rem;
            border-radius: 10px;
            background: #EFF6FF;
            color: #2563EB;
            border: 1px solid #BFDBFE;
            font-size: .8rem;
            font-weight: 700;
            text-decoration: none;
            transition: all .2s ease;
        }

        .admin-shell .btn-lms:hover {
            background: #DBEAFE;
            color: #1D4ED8;
        }

        .admin-shell .icon-btn-lite {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: 1px solid #E2E8F0;
            background: #ffffff;
            color: #475569;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all .2s ease;
        }

        .admin-shell .icon-btn-lite:hover {
            background: #F8FAFC;
            color: #0F172A;
            border-color: #CBD5E1;
        }

        .admin-shell .user-dropdown .dropdown-toggle {
            display: flex;
            align-items: center;
            gap: .55rem;
            padding: .35rem .55rem;
            border-radius: 10px;
            border: 1px solid #E2E8F0;
            background: #ffffff;
        }

        .admin-shell .user-dropdown .dropdown-toggle::after {
            display: none;
        }

        .admin-shell .avatar-dot {
            width: 28px;
            height: 28px;
            border-radius: 9999px;
            background: #2563EB;
            color: #ffffff;
            font-size: .75rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 1200px) {
            .admin-shell .admin-sidebar {
                position: fixed;
                left: 0;
                top: 0;
                transform: translateX(-100%);
                transition: transform .22s ease;
                width: 272px;
                box-shadow: 18px 0 40px rgba(15, 23, 42, .12);
            }

            .admin-shell .admin-sidebar.open {
                transform: translateX(0);
            }

            .admin-shell .side-toggle {
                display: inline-flex;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="admin-shell">
    @php
        $user = auth()->user();
        $initials = $user ? strtoupper(substr($user->name, 0, 1)) : 'A';
        $isSuper = (bool) ($user->is_superadmin ?? false);
        $showTopSearch = !request()->routeIs('admin.dashboard');
    @endphp
    <div class="admin-app">
        <aside id="adminSidebar" class="admin-sidebar">
            <div>
                <a class="brand" href="{{ route('admin.dashboard') }}">
                    <span class="brand-badge"><i class="fa-solid fa-guitar text-base"></i></span>
                    <div>
                        <div style="font-size: 0.95rem; font-weight: 800; color: #0F172A; line-height: 1.2;">Guitarclassbynde</div>
                        <span style="font-size: 0.65rem; padding: 2px 8px; border-radius: 9999px; background: #EFF6FF; color: #2563EB; border: 1px solid #BFDBFE; font-weight: 700; display: inline-block;">Admin Panel</span>
                    </div>
                </a>

                <div class="menu-label">MAIN MENU</div>
                <a class="side-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-grip-vertical"></i>
                        <span>Dashboard</span>
                    </div>
                    @if(request()->routeIs('admin.dashboard'))
                        <span class="rounded-circle bg-primary" style="width: 6px; height: 6px;"></span>
                    @endif
                </a>
                <a class="side-link {{ request()->is('admin/lessons*') ? 'active' : '' }}" href="{{ route('admin.lessons.index') }}">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-book-open"></i>
                        <span>Lessons</span>
                    </div>
                </a>
                <a class="side-link {{ request()->is('admin/song-tabs*') ? 'active' : '' }}" href="{{ route('admin.song-tabs.index') }}">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-music text-warning"></i>
                        <span>Songsterr TABs</span>
                    </div>
                </a>
                <a class="side-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="{{ route('admin.users.packages') }}">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-users"></i>
                        <span>Users</span>
                    </div>
                </a>
                <a class="side-link {{ request()->is('admin/coaching/bookings*') ? 'active' : '' }}" href="{{ url('/admin/coaching/bookings') }}">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-calendar-check"></i>
                        <span>Booking Coaching</span>
                    </div>
                </a>
                <a class="side-link {{ request()->is('admin/coaching/slot-capacities*') ? 'active' : '' }}" href="{{ url('/admin/coaching/slot-capacities') }}">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-clock"></i>
                        <span>Slot Capacity</span>
                    </div>
                </a>
                <a class="side-link {{ request()->is('admin/packages*') ? 'active' : '' }}" href="{{ route('admin.packages.index') }}">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-box-open"></i>
                        <span>Packages</span>
                    </div>
                </a>
                <a class="side-link {{ request()->is('admin/transactions*') ? 'active' : '' }}" href="{{ route('admin.transactions.index') }}">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-receipt"></i>
                        <span>Transactions</span>
                    </div>
                </a>
                <a class="side-link {{ request()->is('admin/faq*') ? 'active' : '' }}" href="{{ route('admin.faq.index') }}">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-question"></i>
                        <span>FAQ</span>
                    </div>
                </a>

                @if($isSuper)
                    <div class="menu-label">SUPER ADMIN</div>
                    <a class="side-link {{ request()->is('admin/vouchers*') ? 'active' : '' }}" href="{{ route('admin.vouchers.index') }}">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-ticket"></i>
                            <span>Vouchers</span>
                        </div>
                    </a>
                    <a class="side-link {{ request()->is('admin/referrals*') ? 'active' : '' }}" href="{{ route('admin.referrals.index') }}">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-share-nodes"></i>
                            <span>Referral</span>
                        </div>
                    </a>
                    <a class="side-link {{ request()->routeIs('admin.videopromo') ? 'active' : '' }}" href="{{ route('admin.videopromo') }}">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-video"></i>
                            <span>Video Promo</span>
                        </div>
                    </a>
                    <a class="side-link {{ request()->is('admin/settings*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-gear"></i>
                            <span>Settings</span>
                        </div>
                    </a>
                @endif
            </div>

            <div class="sidebar-footer">
                <div class="status-box">
                    <div class="d-flex align-items-center gap-2">
                        <span class="position-relative d-inline-flex" style="width: 8px; height: 8px;">
                            <span class="position-absolute w-100 h-100 rounded-circle bg-success opacity-75 animate-ping"></span>
                            <span class="position-relative w-100 h-100 rounded-circle bg-success"></span>
                        </span>
                        <span style="font-size: 0.75rem; font-weight: 600; color: #334155;">Live System Online</span>
                    </div>
                    <span style="font-size: 0.65rem; padding: 2px 8px; border-radius: 6px; background: #ffffff; color: #475569; border: 1px solid #CBD5E1; font-weight: 700; font-family: monospace; display: inline-block;">v2.4</span>
                </div>
            </div>
        </aside>

        <main class="admin-main">
            <header class="admin-topbar">
                <div class="topbar-inner">
                    <button id="sidebarToggle" class="side-toggle" type="button" aria-label="Toggle sidebar">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    @if ($showTopSearch)
                        <div class="top-search">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input id="adminTopSearchInput" type="text" placeholder="Search menu cepat: lesson, users, booking, invoice..." aria-label="Search admin menu" autocomplete="off">
                            <kbd>⌘K</kbd>
                            <div id="adminTopSearchResults" class="top-search-results" role="listbox" aria-label="Search menu results"></div>
                        </div>
                    @endif
                    <div class="top-actions">
                        <a class="btn-lms" href="{{ route('lms.entry') }}" target="_blank" rel="noopener" aria-label="Buka LMS">
                            <i class="fa-solid fa-graduation-cap"></i>
                            <span>Open LMS</span>
                            <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 10px; opacity: 0.7;"></i>
                        </a>
                        <a class="icon-btn-lite" href="{{ route('admin.dashboard') }}" title="Refresh dashboard"><i class="fa-solid fa-rotate" style="font-size: 12px;"></i></a>
                        <div class="dropdown user-dropdown">
                            <a class="dropdown-toggle text-decoration-none" href="#" id="adminUserMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="avatar-dot">{{ $initials }}</span>
                                <div class="text-start d-none d-sm-block me-1">
                                    <div style="font-size: 0.8rem; font-weight: 700; color: #0F172A; line-height: 1;">Admin</div>
                                    <div style="font-size: 0.68rem; color: #64748B;">Guitarclassbynde</div>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminUserMenu">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="dropdown-item p-0 m-0">
                                        @csrf
                                        <button type="submit" class="w-100 text-start border-0 bg-transparent px-3 py-2 text-danger fw-semibold" style="font-size: 0.82rem;">
                                            <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

            <div class="admin-content">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <div aria-live="polite" aria-atomic="true" class="position-relative">
      <div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3"></div>
    </div>

    <script>
        document.addEventListener('click', function(e){
            const target = e.target;
            if (target.matches('.btn-accept') || target.matches('.btn-reject')){
                e.preventDefault();
                const form = target.closest('form');
                const action = target.classList.contains('btn-accept') ? 'accept' : 'reject';
                if (!confirm('Are you sure you want to ' + action + ' this booking?')) return;
                form.submit();
            }
        });

        // show toast from session
        window.addEventListener('DOMContentLoaded', function(){
            @if(session('success'))
                showToast("{{ session('success') }}", 'success');
            @endif
        });

        function showToast(message, type='info'){
            const container = document.getElementById('toastContainer');
            const toastElem = document.createElement('div');
            toastElem.className = 'toast align-items-center text-bg-' + (type==='success' ? 'success' : 'secondary') + ' border-0';
            toastElem.role = 'alert';
            toastElem.ariaLive = 'assertive';
            toastElem.ariaAtomic = 'true';
            toastElem.innerHTML = `<div class="d-flex"><div class="toast-body">${message}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button></div>`;
            container.appendChild(toastElem);
            const t = new bootstrap.Toast(toastElem, { delay: 4000 });
            t.show();
        }

        const sidebarToggle = document.getElementById('sidebarToggle');
        const adminSidebar = document.getElementById('adminSidebar');
        if (sidebarToggle && adminSidebar) {
            sidebarToggle.addEventListener('click', function () {
                adminSidebar.classList.toggle('open');
            });

            document.addEventListener('click', function (e) {
                if (window.innerWidth > 1200) return;
                if (!adminSidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    adminSidebar.classList.remove('open');
                }
            });
        }

        (function initTopSearch() {
            const input = document.getElementById('adminTopSearchInput');
            const results = document.getElementById('adminTopSearchResults');
            if (!input || !results) return;

            const menuLinks = Array.from(document.querySelectorAll('.admin-sidebar .side-link'));
            const items = menuLinks.map((link) => ({
                label: (link.textContent || '').trim().replace(/\s+/g, ' '),
                href: link.getAttribute('href') || '#'
            })).filter((item) => item.label && item.href && item.href !== '#');

            let filteredItems = [];
            let activeIndex = -1;

            function normalize(text) {
                return (text || '').toLowerCase().trim();
            }

            function hideResults() {
                results.classList.remove('show');
                results.innerHTML = '';
                activeIndex = -1;
                filteredItems = [];
            }

            function goToItem(index) {
                const item = filteredItems[index];
                if (!item) return;
                window.location.href = item.href;
            }

            function renderResults() {
                const q = normalize(input.value);
                if (!q) {
                    hideResults();
                    return;
                }

                filteredItems = items
                    .map((item) => {
                        const label = normalize(item.label);
                        const startsWith = label.startsWith(q);
                        const contains = label.includes(q);
                        if (!contains) return null;
                        return { ...item, score: startsWith ? 0 : 1 };
                    })
                    .filter(Boolean)
                    .sort((a, b) => a.score - b.score || a.label.localeCompare(b.label))
                    .slice(0, 8);

                if (!filteredItems.length) {
                    results.innerHTML = '<div class="top-search-empty">No matching menu found.</div>';
                    results.classList.add('show');
                    activeIndex = -1;
                    return;
                }

                results.innerHTML = filteredItems.map((item, idx) => (
                    '<button type="button" class="top-search-item' + (idx === activeIndex ? ' active' : '') + '" data-index="' + idx + '">' +
                        '<span>' + item.label + '</span>' +
                        '<small>Enter</small>' +
                    '</button>'
                )).join('');

                results.classList.add('show');
            }

            input.addEventListener('input', function () {
                activeIndex = -1;
                renderResults();
            });

            input.addEventListener('keydown', function (e) {
                if (!results.classList.contains('show')) return;

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (!filteredItems.length) return;
                    activeIndex = (activeIndex + 1) % filteredItems.length;
                    renderResults();
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (!filteredItems.length) return;
                    activeIndex = (activeIndex - 1 + filteredItems.length) % filteredItems.length;
                    renderResults();
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (activeIndex >= 0 && activeIndex < filteredItems.length) {
                        goToItem(activeIndex);
                    } else if (filteredItems.length) {
                        goToItem(0);
                    }
                } else if (e.key === 'Escape') {
                    hideResults();
                }
            });

            results.addEventListener('click', function (e) {
                const btn = e.target.closest('.top-search-item');
                if (!btn) return;
                const idx = Number(btn.getAttribute('data-index'));
                if (Number.isNaN(idx)) return;
                goToItem(idx);
            });

            document.addEventListener('click', function (e) {
                if (!results.classList.contains('show')) return;
                const withinSearch = e.target.closest('.top-search');
                if (!withinSearch) hideResults();
            });
        })();
    </script>
    <script>
        const optionMenu = document.querySelector(".select-menu"),
            selectBtn = optionMenu ? optionMenu.querySelector(".select-btn") : null,
            options = optionMenu ? optionMenu.querySelectorAll(".option") : [],
            btn_text = optionMenu ? optionMenu.querySelector(".btn-text") : null,
            hiddenInput = optionMenu ? optionMenu.querySelector("input[type='hidden']") : null;

        if (optionMenu && selectBtn && btn_text && hiddenInput) {
            // toggle dropdown
            selectBtn.addEventListener("click", () => optionMenu.classList.toggle("active"));

            // pilih option
            options.forEach(option => {
                option.addEventListener("click", () => {
                    let value = option.getAttribute("data-value");
                    let text = option.querySelector(".option-text").innerText;

                    btn_text.innerText = text;
                    hiddenInput.value = value;
                    optionMenu.classList.remove("active");
                });
            });

            // Tutup dropdown jika klik di luar
            document.addEventListener("click", (e) => {
                if (!optionMenu.contains(e.target)) {
                    optionMenu.classList.remove("active");
                }
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
