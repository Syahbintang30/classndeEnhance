@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<style>
    body > nav { display: none; }

    :root {
        --ep-bg: #0a0a0a;
        --ep-text: #ffffff;
        --ep-muted: rgba(255,255,255,0.55);
        --ep-card: rgba(24,24,27,0.6);
        --ep-border: rgba(255,255,255,0.08);
        --ep-input-bg: rgba(10,10,10,0.5);
        --ep-input-border: rgba(255,255,255,0.08);
        --ep-label: rgba(255,255,255,0.7);
        --ep-section-bg: linear-gradient(180deg, rgba(30,30,30,0.6) 0%, rgba(20,20,20,0.4) 100%);
    }

    :root[data-theme="light"] {
        --ep-bg: #f5f5f7;
        --ep-text: #0f172a;
        --ep-muted: rgba(15,23,42,0.55);
        --ep-card: #ffffff;
        --ep-border: rgba(15,23,42,0.08);
        --ep-input-bg: #ffffff;
        --ep-input-border: rgba(15,23,42,0.12);
        --ep-label: #475569;
        --ep-section-bg: #ffffff;
    }

    html, body { background: var(--ep-bg) !important; color: var(--ep-text) !important; overflow-x: hidden; }
    *, *::before, *::after { box-sizing: border-box; }

    /* Navbar */
    .lms-navbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 72px;
        background: linear-gradient(180deg, #111 0%, #0a0a0a 100%);
        border-bottom: 1px solid rgba(255,255,255,0.06);
        padding: 0 28px;
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .lms-home-link {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #e0e0e0;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: color 0.2s;
    }

    .lms-home-link:hover { color: #fff; }

    .lms-navbar-right { display: flex; align-items: center; gap: 28px; }

    .lms-nav-link {
        color: #888;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        transition: color 0.2s;
    }

    .lms-nav-link:hover, .lms-nav-link.active { color: #fff; font-weight: 600; }

    :root[data-theme="light"] .lms-navbar {
        background: linear-gradient(180deg, #ffffff 0%, #f4f5f7 100%);
        border-bottom-color: rgba(15,23,42,0.08);
    }

    :root[data-theme="light"] .lms-home-link,
    :root[data-theme="light"] .lms-nav-link { color: #475569; }

    :root[data-theme="light"] .lms-home-link:hover,
    :root[data-theme="light"] .lms-nav-link:hover,
    :root[data-theme="light"] .lms-nav-link.active { color: #0f172a; }

    .lms-theme-btn {
        width: 34px; height: 34px;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,0.12);
        background: transparent;
        color: #fff;
        cursor: pointer;
        transition: all 0.2s;
    }

    .lms-theme-btn:hover { background: rgba(255,255,255,0.08); }

    :root[data-theme="light"] .lms-theme-btn {
        border-color: rgba(15,23,42,0.12);
        color: #0f172a;
    }

    :root[data-theme="light"] .lms-theme-btn:hover { background: rgba(15,23,42,0.06); }

    /* Page */
    .ep-page {
        min-height: calc(100vh - 72px);
        background: var(--ep-bg);
        padding: 40px 24px 60px;
    }

    .ep-inner {
        max-width: 720px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .ep-page-title {
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -0.03em;
        color: var(--ep-text);
        margin: 0 0 4px;
    }

    :root[data-theme="light"] .ep-page-title { color: #0f172a; }

    .ep-page-sub {
        font-size: 14px;
        color: var(--ep-muted);
        margin: 0 0 8px;
    }

    /* Section card */
    .ep-section {
        background: var(--ep-section-bg);
        border: 1px solid var(--ep-border);
        border-radius: 18px;
        padding: 28px;
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
    }

    :root[data-theme="light"] .ep-section {
        box-shadow: 0 4px 20px rgba(15,23,42,0.06);
        backdrop-filter: none;
    }

    .ep-section-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--ep-text);
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }

    .ep-section-sub {
        font-size: 13px;
        color: var(--ep-muted);
        margin: 0 0 22px;
    }

    /* Avatar */
    .ep-avatar-row {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
        padding-bottom: 24px;
        border-bottom: 1px solid var(--ep-border);
    }

    .ep-avatar-wrap {
        width: 80px; height: 80px;
        border-radius: 999px;
        overflow: hidden;
        border: 2px solid var(--ep-border);
        background: rgba(255,255,255,0.04);
        flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
    }

    :root[data-theme="light"] .ep-avatar-wrap { background: #f1f5f9; }

    .ep-avatar-wrap img {
        width: 100%;
        height: 100%;
        /* Mengubah posisi ke tengah tepat, dan menggunakan cover agar mengisi seluruh area */
        object-position: center; 
        display: block;
        object-fit: cover; 
    }

    .ep-change-photo-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.1);
        padding: 9px 14px;
        border-radius: 10px;
        color: #fff;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .ep-change-photo-btn:hover { background: rgba(255,255,255,0.1); }

    :root[data-theme="light"] .ep-change-photo-btn {
        background: rgba(15,23,42,0.04);
        border-color: rgba(15,23,42,0.12);
        color: #0f172a;
    }

    :root[data-theme="light"] .ep-change-photo-btn:hover { background: rgba(15,23,42,0.08); }

    /* Form fields */
    .ep-field { margin-bottom: 18px; }

    .ep-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--ep-label);
        margin-bottom: 7px;
    }

    .ep-input {
        width: 100%;
        padding: 11px 14px;
        border-radius: 10px;
        background: var(--ep-input-bg);
        border: 1px solid var(--ep-input-border);
        color: var(--ep-text);
        font-size: 14px;
        font-family: inherit;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
    }

    .ep-input:focus {
        border-color: rgba(255,255,255,0.25);
        box-shadow: 0 0 0 3px rgba(255,255,255,0.04);
    }

    :root[data-theme="light"] .ep-input:focus {
        border-color: rgba(15,23,42,0.25);
        box-shadow: 0 0 0 3px rgba(15,23,42,0.04);
    }

    .ep-input-wrap { position: relative; }

    .ep-pw-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: var(--ep-muted);
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
    }

    .ep-input-with-toggle { padding-right: 42px; }

    .ep-field-error { color: #f87171; font-size: 12px; margin-top: 5px; }

    /* Two-column grid for password fields */
    .ep-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    @media (max-width: 560px) { .ep-grid-2 { grid-template-columns: 1fr; } }

    /* Buttons */
    .ep-btn-row { display: flex; gap: 10px; align-items: center; margin-top: 6px; }

    .ep-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #0f172a;
        color: #fff;
        border: none;
        padding: 11px 20px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
    }

    .ep-btn-primary:hover { background: #1e293b; transform: translateY(-1px); }

    :root[data-theme="light"] .ep-btn-primary { background: #0f172a; }

    .ep-btn-cancel {
        display: inline-flex;
        align-items: center;
        color: var(--ep-muted);
        text-decoration: none;
        padding: 11px 16px;
        border-radius: 10px;
        border: 1px solid var(--ep-border);
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .ep-btn-cancel:hover { color: var(--ep-text); border-color: rgba(255,255,255,0.18); }
    :root[data-theme="light"] .ep-btn-cancel:hover { border-color: rgba(15,23,42,0.2); }

    /* Flash */
    .ep-msg { padding: 12px 16px; border-radius: 10px; font-weight: 600; font-size: 14px; margin-bottom: 6px; }
    .ep-msg-success { background: rgba(11,122,68,0.15); color: #86efac; border: 1px solid rgba(34,197,94,0.2); }
    .ep-msg-error { background: rgba(192,57,43,0.15); color: #fca5a5; border: 1px solid rgba(248,113,113,0.2); }
    .ep-msg-error ul { margin: 6px 0 0; padding-left: 18px; }

    /* Cropper modal */
    #cropper-modal { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:2000; align-items:center; justify-content:center; padding:20px; opacity:0; pointer-events:none; transition:opacity .22s ease; }
    #cropper-modal.open { opacity:1; pointer-events:auto; }
    #cropper-dialog { transform:translateY(6px) scale(.98); transition:transform .22s cubic-bezier(.2,.9,.2,1), opacity .18s ease; opacity:.98; max-height:calc(100vh - 80px); overflow:auto; box-sizing:border-box; }
    #cropper-modal.open #cropper-dialog { transform:translateY(0) scale(1); opacity:1; }
    #crop-area { width:min(400px,82vw); aspect-ratio:1/1; height:auto; background:#111; overflow:hidden; position:relative; border-radius:10px; border:1px solid rgba(255,255,255,0.06); touch-action:none; }
    #crop-area img { position:absolute; left:0; top:0; will-change:transform; user-select:none; -webkit-user-drag:none; }
    #crop-zoom { width:200px; max-width:55vw; }
</style>

<!-- Navbar -->
<nav class="lms-navbar">
    <a href="{{ route('lms.dashboard') }}" class="lms-home-link">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            <polyline points="9 22 9 12 15 12 15 22"></polyline>
        </svg>
        Home
    </a>
    <div class="lms-navbar-right">
        <a href="{{ route('lms.entry') }}" class="lms-nav-link">Lessons</a>
        <a href="{{ route('coaching.upcoming') }}" class="lms-nav-link">Coaching</a>
        @php $user = auth()->user(); @endphp
        @if($user && $user->hasLmsAccess())
            <a href="{{ route('song.tutorial.index') }}" class="lms-nav-link">Song Tutorial</a>
        @endif
        <button id="theme-toggle-ep" type="button" class="lms-theme-btn" aria-label="Toggle theme">
            <svg id="ep-moon" style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
            <svg id="ep-sun" style="width:15px;height:15px;display:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
        </button>
    </div>
</nav>

<div class="ep-page">
    <div class="ep-inner">

        <div>
            <h1 class="ep-page-title">Edit Profil</h1>
            <p class="ep-page-sub">Kelola informasi akun dan keamanan Anda</p>
        </div>

        <!-- Flash Messages -->
        @if(session('status') || session('success') || session('error') || $errors->any())
        <div>
            @if(session('status') === 'profile-updated' || session('success'))
                <div class="ep-msg ep-msg-success">{{ session('success') ?? 'Profile updated.' }}</div>
            @endif
            @if(session('status') === 'password-updated')
                <div class="ep-msg ep-msg-success">Password updated.</div>
            @endif
            @if(session('error'))
                <div class="ep-msg ep-msg-error">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="ep-msg ep-msg-error">
                    <ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
                </div>
            @endif
        </div>
        @endif

        <!-- Profile Info Section -->
        <div class="ep-section">
            <h2 class="ep-section-title">Informasi Profil</h2>
            <p class="ep-section-sub">Perbarui detail akun dan alamat email Anda.</p>

            <!-- Avatar -->
            <div class="ep-avatar-row">
                @php $avatar = $user->photoUrl(); @endphp
                <div class="ep-avatar-wrap">
                    @if($avatar)
                        <img id="photo-preview" src="{{ $avatar }}" alt="avatar">
                    @else
                        <img id="photo-preview" src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><circle cx='12' cy='8' r='4' fill='%23888'/><path d='M4 20c0-4 4-6 8-6s8 2 8 6' fill='%23888'/></svg>" alt="avatar">
                    @endif
                </div>
                <div>
                    <button id="change-photo" type="button" class="ep-change-photo-btn">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.1 2.1 0 0 1 2.97 2.97L8 18l-4 1 1-4 11.5-11.5z"></path></svg>
                        Ganti Foto
                    </button>
                    <p style="font-size:12px;color:var(--ep-muted);margin:6px 0 0;">JPG, PNG maks. 2MB</p>
                </div>
            </div>

            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <input id="photo" name="photo" type="file" accept="image/*" style="display:none">

                <div class="ep-field">
                    <label class="ep-label" for="name">Nama Lengkap</label>
                    <input class="ep-input" id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required maxlength="255">
                    @if($errors->has('name'))<div class="ep-field-error">{{ $errors->first('name') }}</div>@endif
                </div>

                <div class="ep-field">
                    <label class="ep-label" for="email">Alamat Email</label>
                    <input class="ep-input" id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required maxlength="255">
                    @if($errors->has('email'))<div class="ep-field-error">{{ $errors->first('email') }}</div>@endif
                </div>

                <div class="ep-btn-row">
                    <button type="submit" class="ep-btn-primary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('profile') }}" class="ep-btn-cancel">Batal</a>
                </div>
            </form>
        </div>

        <!-- Password Section -->
        <div class="ep-section" id="password">
            <h2 class="ep-section-title">Ubah Kata Sandi</h2>
            <p class="ep-section-sub">Pastikan akun Anda menggunakan kata sandi yang kuat dan unik.</p>

            <form method="post" action="{{ route('password.update') }}">
                @csrf
                @method('put')

                <div class="ep-field">
                    <label class="ep-label" for="current_password">Kata Sandi Saat Ini</label>
                    <div class="ep-input-wrap">
                        <input class="ep-input ep-input-with-toggle" id="current_password" name="current_password" type="password" required autocomplete="current-password" placeholder="••••••••">
                        <button type="button" class="ep-pw-toggle" data-target="current_password">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
                    </div>
                    @if($errors->has('current_password'))<div class="ep-field-error">{{ $errors->first('current_password') }}</div>@endif
                </div>

                <div class="ep-grid-2">
                    <div class="ep-field">
                        <label class="ep-label" for="password_input">Kata Sandi Baru</label>
                        <div class="ep-input-wrap">
                            <input class="ep-input ep-input-with-toggle" id="password_input" name="password" type="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
                            <button type="button" class="ep-pw-toggle" data-target="password_input">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </button>
                        </div>
                        @if($errors->has('password'))<div class="ep-field-error">{{ $errors->first('password') }}</div>@endif
                    </div>

                    <div class="ep-field">
                        <label class="ep-label" for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
                        <div class="ep-input-wrap">
                            <input class="ep-input ep-input-with-toggle" id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" placeholder="Ulangi kata sandi baru">
                            <button type="button" class="ep-pw-toggle" data-target="password_confirmation">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </button>
                        </div>
                        @if($errors->has('password_confirmation'))<div class="ep-field-error">{{ $errors->first('password_confirmation') }}</div>@endif
                    </div>
                </div>

                <div class="ep-btn-row">
                    <button type="submit" class="ep-btn-primary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        Simpan Kata Sandi
                    </button>
                    <a href="{{ route('profile') }}" class="ep-btn-cancel">Batal</a>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- Cropper Modal -->
<div id="cropper-modal">
    <div id="cropper-dialog" style="width:100%;max-width:720px;background:linear-gradient(180deg,#0b0b0b,#0f0f0f);border-radius:14px;padding:20px;border:1px solid rgba(255,255,255,0.06);box-shadow:0 24px 80px rgba(0,0,0,0.6);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <div style="font-weight:700;color:#fff;font-size:15px;">Sesuaikan Foto</div>
            <div style="display:flex;gap:8px;">
                <button id="crop-remove" type="button" style="background:transparent;border:1px solid rgba(255,255,255,0.08);padding:7px 12px;border-radius:8px;color:#fff;cursor:pointer;font-size:13px;">Hapus Foto</button>
                <button id="crop-cancel" type="button" style="background:transparent;border:1px solid rgba(255,255,255,0.08);padding:7px 12px;border-radius:8px;color:#fff;cursor:pointer;font-size:13px;">Batal</button>
                <button id="crop-apply" type="button" style="background:#0f172a;border:none;padding:7px 14px;border-radius:8px;color:#fff;font-weight:700;cursor:pointer;font-size:13px;">Terapkan</button>
            </div>
        </div>
        <div style="display:flex;flex-direction:column;align-items:center;gap:14px;">
            <div id="crop-area">
                <img id="crop-image" src="" alt="to crop">
                <div style="position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:80%;height:80%;border:2px dashed rgba(255,255,255,0.2);box-shadow:0 0 0 9999px rgba(0,0,0,0.35) inset;pointer-events:none;border-radius:8px"></div>
            </div>
            <div style="display:flex;gap:10px;align-items:center;">
                <span style="font-size:13px;color:rgba(255,255,255,0.7);font-weight:600;">Zoom</span>
                <input id="crop-zoom" type="range" min="0.5" max="3" step="0.01" value="1">
            </div>
        </div>
    </div>
</div>

<script>
// Scroll to password section if hash
(function(){
    if (window.location.hash === '#password') {
        var el = document.getElementById('password');
        if (el) setTimeout(function(){ el.scrollIntoView({behavior:'smooth',block:'start'}); var inp = el.querySelector('input[name="current_password"]'); if(inp) inp.focus(); }, 80);
    }
})();

// Password toggles
document.querySelectorAll('.ep-pw-toggle').forEach(function(btn){
    btn.addEventListener('click', function(){
        var input = document.getElementById(btn.getAttribute('data-target'));
        if(!input) return;
        input.type = input.type === 'password' ? 'text' : 'password';
    });
});

// Theme toggle
(function(){
    function getTheme(){ var m = document.cookie.match(/(?:^|; )theme=([^;]+)/); return m ? decodeURIComponent(m[1]) : 'dark'; }
    function setTheme(theme){
        document.documentElement.setAttribute('data-theme', theme);
        document.cookie = 'theme=' + encodeURIComponent(theme) + '; path=/; max-age=31536000; SameSite=Lax';
        var moon = document.getElementById('ep-moon'); var sun = document.getElementById('ep-sun');
        if(moon && sun){ moon.style.display = theme === 'light' ? 'none' : 'block'; sun.style.display = theme === 'light' ? 'block' : 'none'; }
    }
    setTheme(getTheme());
    var btn = document.getElementById('theme-toggle-ep');
    if(btn) btn.addEventListener('click', function(){ setTheme(getTheme() === 'light' ? 'dark' : 'light'); });
})();

// Cropper
(function(){
    var changeBtn = document.getElementById('change-photo');
    var nativeInput = document.getElementById('photo');
    var preview = document.getElementById('photo-preview');
    var modal = document.getElementById('cropper-modal');
    var cropImage = document.getElementById('crop-image');
    var cropArea = document.getElementById('crop-area');
    var zoom = document.getElementById('crop-zoom');
    var apply = document.getElementById('crop-apply');
    var cancel = document.getElementById('crop-cancel');
    var rem = document.getElementById('crop-remove');
    var state = { x:0, y:0, scale:1, isDown:false, startX:0, startY:0 };

    function showModal(){ modal.style.display='flex'; requestAnimationFrame(function(){ modal.classList.add('open'); }); document.body.style.overflow='hidden'; }
    function hideModal(){ modal.classList.remove('open'); setTimeout(function(){ modal.style.display='none'; document.body.style.overflow=''; }, 240); }

    changeBtn.addEventListener('click', function(){ nativeInput.click(); });

    nativeInput.addEventListener('change', function(){
        var f = this.files && this.files[0]; if(!f) return;
        var reader = new FileReader();
        reader.onload = function(e){
            cropImage.src = e.target.result;
            cropImage.onload = function(){ state.scale=1; state.x=0; state.y=0; updateTransform(); zoom.value=1; showModal(); };
        };
        reader.readAsDataURL(f);
    });

    cropImage.addEventListener('pointerdown', function(e){ state.isDown=true; state.startX=e.clientX; state.startY=e.clientY; cropImage.setPointerCapture(e.pointerId); });
    window.addEventListener('pointermove', function(e){ if(!state.isDown) return; state.x += e.clientX-state.startX; state.y += e.clientY-state.startY; state.startX=e.clientX; state.startY=e.clientY; updateTransform(); });
    window.addEventListener('pointerup', function(){ state.isDown=false; });
    zoom.addEventListener('input', function(){ state.scale=parseFloat(this.value); updateTransform(); });
    function updateTransform(){ cropImage.style.transform='translate('+state.x+'px,'+state.y+'px) scale('+state.scale+')'; }

    apply.addEventListener('click', function(){
        var outSize = Math.min(800, Math.round(cropArea.clientWidth * (window.devicePixelRatio||1)));
        var canvas = document.createElement('canvas'); canvas.width=outSize; canvas.height=outSize;
        var ctx = canvas.getContext('2d');
        var areaW=cropArea.clientWidth; var areaH=cropArea.clientHeight;
        var iRW=cropImage.naturalWidth*state.scale; var iRH=cropImage.naturalHeight*state.scale;
        var offX=(areaW/2)-(iRW/2)+state.x; var offY=(areaH/2)-(iRH/2)+state.y;
        var srcX=Math.max(0,Math.round((-offX)/state.scale)); var srcY=Math.max(0,Math.round((-offY)/state.scale));
        var srcW=Math.min(cropImage.naturalWidth-srcX,Math.round(areaW/state.scale));
        var srcH=Math.min(cropImage.naturalHeight-srcY,Math.round(areaH/state.scale));
        try{ ctx.fillStyle='#111'; ctx.fillRect(0,0,outSize,outSize); ctx.drawImage(cropImage,srcX,srcY,srcW,srcH,0,0,outSize,outSize); }catch(e){ alert('Gagal crop gambar'); hideModal(); return; }
        canvas.toBlob(function(blob){
            if(!blob){ alert('Gagal membuat gambar'); hideModal(); return; }
            var file = new File([blob],'profile.jpg',{type:'image/jpeg'});
            var dt = new DataTransfer(); dt.items.add(file); nativeInput.files=dt.files;
            preview.src = URL.createObjectURL(file);
            hideModal();
        },'image/jpeg',0.9);
    });

    cancel.addEventListener('click', function(){ nativeInput.value=''; hideModal(); });

    rem.addEventListener('click', function(){
        if(!confirm('Hapus foto profil?')) return;
        var f=document.createElement('form'); f.method='POST'; f.action='{{ route('profile.update') }}'; f.style.display='none';
        var t=document.createElement('input'); t.type='hidden'; t.name='_token'; t.value='{{ csrf_token() }}'; f.appendChild(t);
        var m=document.createElement('input'); m.type='hidden'; m.name='_method'; m.value='PATCH'; f.appendChild(m);
        var r=document.createElement('input'); r.type='hidden'; r.name='remove_photo'; r.value='1'; f.appendChild(r);
        document.body.appendChild(f); f.submit();
    });
})();
</script>
@endsection