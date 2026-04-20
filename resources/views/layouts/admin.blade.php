<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - @yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('compro/img/ndelogo.png') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSS -->
    <!-- Load Bootstrap first so admin.css can override Bootstrap defaults -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('compro/css/admin.css') }}" rel="stylesheet" type="text/css" media="all" />
        <link href="{{ asset('compro/css/admin-unified.css') }}" rel="stylesheet" type="text/css" media="all" />
    <link
      rel="stylesheet"
      type="text/css"
      href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/duotone/style.css"
    />
    <link
      rel="stylesheet"
      type="text/css"
      href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css"
    />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --adm-bg: #050505;
            --adm-surface: #0a0a0a;
            --adm-card: #111111;
            --adm-border: rgba(255, 255, 255, 0.10);
            --adm-text: #f5f5f5;
            --adm-muted: #8a8a8a;
            --adm-primary: #ffffff;
            --adm-primary-soft: rgba(255, 255, 255, 0.08);
            --adm-ok: #22c55e;
            --adm-warn: #f43f5e;
            --adm-shadow: 0 18px 36px rgba(0, 0, 0, 0.45);
        }

        body.admin-shell,
        body.admin-shell input,
        body.admin-shell textarea,
        body.admin-shell select,
        body.admin-shell button {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, 'Segoe UI', sans-serif;
        }

        body.admin-shell {
            margin: 0;
            background: radial-gradient(circle at 100% -20%, rgba(255, 255, 255, 0.05) 0%, #050505 40%) !important;
            color: var(--adm-text) !important;
        }

        .admin-shell .admin-app {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 264px minmax(0, 1fr);
        }

        .admin-shell .admin-sidebar {
            background: var(--adm-surface) !important;
            border-right: 1px solid var(--adm-border) !important;
            padding: 1.2rem 1rem;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1021;
        }

        .admin-shell .brand {
            display: flex;
            align-items: center;
            gap: .7rem;
            text-decoration: none;
            color: var(--adm-text);
            font-weight: 800;
            letter-spacing: -.02em;
            margin-bottom: 1.2rem;
        }

        .admin-shell .brand-badge {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #050505;
            background: linear-gradient(145deg, #ffffff, #d4d4d4);
            box-shadow: 0 10px 24px rgba(0, 0, 0, .45);
        }

        .admin-shell .menu-label {
            font-size: .72rem;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--adm-muted);
            margin: .95rem .65rem .45rem;
            font-weight: 700;
        }

        .admin-shell .side-link {
            display: flex;
            align-items: center;
            gap: .65rem;
            padding: .65rem .75rem;
            border-radius: 10px;
            text-decoration: none;
            color: #d4d4d4;
            font-size: .92rem;
            font-weight: 600;
            transition: background .16s ease, color .16s ease, transform .16s ease;
            margin-bottom: .2rem;
        }

        .admin-shell .side-link:hover {
            background: rgba(255, 255, 255, 0.06);
            color: #ffffff;
            transform: translateX(1px);
        }

        .admin-shell .side-link.active {
            color: var(--adm-primary);
            background: var(--adm-primary-soft);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .14);
        }

        .admin-shell .side-link i {
            font-size: 1rem;
        }

        .admin-shell .admin-main {
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .admin-shell .admin-topbar {
            position: sticky;
            top: 0;
            z-index: 1010;
            background: rgba(5, 5, 5, .88) !important;
            border-bottom: 1px solid var(--adm-border) !important;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .admin-shell .topbar-inner {
            display: flex;
            align-items: center;
            gap: .8rem;
            padding: .85rem 1.15rem;
        }

        .admin-shell .side-toggle {
            border: 1px solid var(--adm-border);
            background: var(--adm-card);
            color: #f5f5f5;
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
        }

        .admin-shell .top-search input {
            width: 100%;
            border: 1px solid var(--adm-border);
            background: var(--adm-card);
            border-radius: 12px;
            height: 42px;
            padding: 0 .95rem 0 2.2rem;
            color: #f5f5f5;
        }

        .admin-shell .top-search i {
            position: absolute;
            left: .78rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--adm-muted);
            font-size: .95rem;
        }

        .admin-shell .top-search-results {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            background: #0b0b0b;
            border: 1px solid var(--adm-border);
            border-radius: 12px;
            box-shadow: 0 16px 42px rgba(0, 0, 0, .45);
            overflow: hidden;
            z-index: 1050;
            display: none;
            max-height: 300px;
            overflow-y: auto;
        }

        .admin-shell .top-search-results.show {
            display: block;
        }

        .admin-shell .top-search-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .7rem;
            width: 100%;
            text-align: left;
            border: 0;
            background: transparent;
            color: #ececec;
            padding: .62rem .78rem;
            font-size: .9rem;
            cursor: pointer;
        }

        .admin-shell .top-search-item:hover,
        .admin-shell .top-search-item.active {
            background: rgba(255, 255, 255, 0.08);
        }

        .admin-shell .top-search-item small {
            color: var(--adm-muted);
            font-size: .73rem;
            white-space: nowrap;
        }

        .admin-shell .top-search-empty {
            padding: .72rem .78rem;
            color: var(--adm-muted);
            font-size: .85rem;
        }

        .admin-shell .top-actions {
            display: flex;
            align-items: center;
            gap: .45rem;
            margin-left: auto;
        }

        .admin-shell .icon-btn-lite {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: 1px solid var(--adm-border);
            background: var(--adm-card);
            color: #d4d4d4;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .admin-shell .btn-lms {
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, .28);
            background: #ffffff;
            color: #050505;
            padding: .45rem .75rem;
            font-size: .85rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            line-height: 1;
            white-space: nowrap;
        }

        .admin-shell .btn-lms,
        .admin-shell .btn-lms:visited,
        .admin-shell .btn-lms:hover,
        .admin-shell .btn-lms:focus {
            color: #050505 !important;
            text-decoration: none;
        }

        .admin-shell .user-dropdown > a {
            border: 1px solid var(--adm-border);
            border-radius: 999px;
            background: var(--adm-card);
            color: #f5f5f5;
            padding: .35rem .6rem;
            font-size: .85rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: .45rem;
        }

        .admin-shell .avatar-dot {
            width: 28px;
            height: 28px;
            border-radius: 999px;
            background: linear-gradient(140deg, #ffffff, #d4d4d4);
            color: #050505;
            font-size: .75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
        }

        .admin-shell .admin-content {
            padding: 1.25rem;
        }

        .admin-shell .dropdown-menu {
            border: 1px solid var(--adm-border);
            border-radius: 12px;
            box-shadow: var(--adm-shadow);
            background: var(--adm-card);
        }

        .admin-shell .dropdown-item {
            color: #e5e5e5;
            font-weight: 600;
        }

        .admin-shell .dropdown-item button {
            width: 100%;
            text-align: left;
            border: none;
            background: transparent;
            color: #e5e5e5;
            font-weight: 600;
            padding: 0;
        }

        .admin-shell .dropdown-item:hover,
        .admin-shell .dropdown-item button:hover {
            color: #ffffff;
            background: transparent;
        }

        @media (max-width: 1200px) {
            .admin-shell .admin-app {
                grid-template-columns: 1fr;
            }

            .admin-shell .admin-sidebar {
                position: fixed;
                left: 0;
                top: 0;
                transform: translateX(-100%);
                transition: transform .22s ease;
                width: 272px;
                box-shadow: 18px 0 40px rgba(15, 23, 42, .25);
            }

            .admin-shell .admin-sidebar.open {
                transform: translateX(0);
            }

            .admin-shell .side-toggle {
                display: inline-flex;
            }
        }
    </style>
    {{-- page-specific styles --}}
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
            <a class="brand" href="{{ route('admin.dashboard') }}">
                <span class="brand-badge"><i class="ph ph-chart-pie-slice"></i></span>
                <span>ClassNDE Admin</span>
            </a>

            <div class="menu-label">Main</div>
            <a class="side-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="ph ph-squares-four"></i> Dashboard
            </a>
            <a class="side-link {{ request()->is('admin/lessons*') ? 'active' : '' }}" href="{{ route('admin.lessons.index') }}">
                <i class="ph ph-book-open-text"></i> Lessons
            </a>
            <a class="side-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="{{ route('admin.users.packages') }}">
                <i class="ph ph-users-three"></i> Users
            </a>
            <a class="side-link {{ request()->is('admin/coaching/bookings*') ? 'active' : '' }}" href="{{ url('/admin/coaching/bookings') }}">
                <i class="ph ph-calendar-check"></i> Booking
            </a>
            <a class="side-link {{ request()->is('admin/coaching/slot-capacities*') ? 'active' : '' }}" href="{{ url('/admin/coaching/slot-capacities') }}">
                <i class="ph ph-timer"></i> Slot Capacity
            </a>

            @if($isSuper)
                <div class="menu-label">Super Admin</div>
                <a class="side-link {{ request()->is('admin/packages*') ? 'active' : '' }}" href="{{ route('admin.packages.index') }}">
                    <i class="ph ph-package"></i> Packages
                </a>
                <a class="side-link {{ request()->is('admin/transactions*') ? 'active' : '' }}" href="{{ route('admin.transactions.index') }}">
                    <i class="ph ph-credit-card"></i> Transactions
                </a>
                <a class="side-link {{ request()->is('admin/vouchers*') ? 'active' : '' }}" href="{{ route('admin.vouchers.index') }}">
                    <i class="ph ph-ticket"></i> Vouchers
                </a>
                <a class="side-link {{ request()->is('admin/payment-methods*') ? 'active' : '' }}" href="{{ route('admin.payment-methods.index') }}">
                    <i class="ph ph-wallet"></i> Payment Methods
                </a>
                <a class="side-link {{ request()->is('admin/referral*') ? 'active' : '' }}" href="{{ route('admin.referral.settings.form') }}">
                    <i class="ph ph-share-network"></i> Referral
                </a>
                <a class="side-link {{ request()->is('admin/settings*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                    <i class="ph ph-gear"></i> Settings
                </a>
            @endif
        </aside>

        <main class="admin-main">
            <header class="admin-topbar">
                <div class="topbar-inner">
                    <button id="sidebarToggle" class="side-toggle" type="button" aria-label="Toggle sidebar">
                        <i class="ph ph-list"></i>
                    </button>
                    @if ($showTopSearch)
                        <div class="top-search">
                            <i class="ph ph-magnifying-glass"></i>
                            <input id="adminTopSearchInput" type="text" placeholder="Search menu cepat: lesson, users, booking..." aria-label="Search admin menu" autocomplete="off">
                            <div id="adminTopSearchResults" class="top-search-results" role="listbox" aria-label="Search menu results"></div>
                        </div>
                    @endif
                    <div class="top-actions">
                        <a class="btn-lms" href="{{ route('lms.entry') }}" target="_blank" rel="noopener" aria-label="Buka LMS">
                            <i class="ph ph-graduation-cap"></i>
                            <span>Buka LMS</span>
                        </a>
                        <a class="icon-btn-lite" href="{{ route('admin.dashboard') }}" title="Refresh dashboard"><i class="ph ph-arrow-clockwise"></i></a>
                        <div class="dropdown user-dropdown">
                            <a class="dropdown-toggle text-decoration-none" href="#" id="adminUserMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="avatar-dot">{{ $initials }}</span>
                                <span>{{ $user->name ?? 'Admin' }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminUserMenu">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="dropdown-item p-0 m-0">
                                        @csrf
                                        <button type="submit">Logout</button>
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
                    results.innerHTML = '<div class="top-search-empty">Tidak ada menu yang cocok.</div>';
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
                    activeIndex = activeIndex <= 0 ? filteredItems.length - 1 : activeIndex - 1;
                    renderResults();
                } else if (e.key === 'Enter') {
                    if (!filteredItems.length) return;
                    e.preventDefault();
                    goToItem(activeIndex >= 0 ? activeIndex : 0);
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

    {{-- section for page scripts --}}
    @yield('scripts')
</body>
</html>
