@extends('layouts.app')

@section('title', 'Dashboard LMS')

@section('content')
<style>
    .lms-dashboard-shell {
        min-height: calc(100vh - 120px);
        background: var(--lms-hero);
        padding: 40px 16px 54px;
        color: var(--lms-text);
    }

    .lms-progress-card {
        border: 1px solid var(--lms-border);
        border-radius: 20px;
        background: var(--lms-card);
        backdrop-filter: blur(10px);
        padding: 28px;
        margin-bottom: 28px;
        box-shadow: var(--lms-shadow);
    }

    .lms-progress-track {
        height: 10px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.05);
        overflow: hidden;
        margin-bottom: 12px;
    }

    .lms-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #00d4ff 0%, #0099ff 100%);
        border-radius: 999px;
        transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    :root[data-theme="light"] .lms-dashboard-shell {
        background: #f6f7fb;
        color: #0f172a;
    }

    :root[data-theme="light"] .lms-progress-card {
        background: #ffffff;
        border-color: rgba(15, 23, 42, 0.10);
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
    }

    :root[data-theme="light"] .lms-progress-track {
        background: rgba(15, 23, 42, 0.10);
    }

    :root[data-theme="light"] .lms-progress-title,
    :root[data-theme="light"] .lms-progress-subtitle,
    :root[data-theme="light"] .lms-progress-remaining {
        color: #475569 !important;
    }

    :root[data-theme="light"] .lms-progress-percent {
        background: linear-gradient(135deg, #007cf0 0%, #00d4ff 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    :root[data-theme="dark"] .lms-dashboard-shell {
        background: linear-gradient(180deg, #0b0b0b 0%, #050505 100%);
    }
</style>
<div class="lms-dashboard-shell">
    <div style="max-width:1200px; margin:0 auto;">
        <!-- Header Section -->
        <div style="margin-bottom:32px;">
            <h1 style="margin:0 0 8px; font-size:42px; line-height:1.1; letter-spacing:-0.03em; font-weight:900; background:var(--lms-heading-gradient); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">Welcome back!</h1>
            <p style="margin:0; color:var(--lms-muted); font-size:15px;">Continue your music learning journey today</p>
        </div>

        <!-- Progress Card -->
        <div class="lms-progress-card">
            <div style="display:flex; align-items:start; justify-content:space-between; margin-bottom:20px;">
                <div>
                    <h2 class="lms-progress-title" style="margin:0 0 6px; font-size:16px; font-weight:700; color:var(--lms-muted); text-transform:uppercase; letter-spacing:1px;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline; margin-right:6px; vertical-align:text-bottom;"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>Your Progress</h2>
                    <p class="lms-progress-subtitle" style="margin:0; color:var(--lms-subtle); font-size:13px;">Topics you have already completed</p>
                </div>
                <div style="text-align:right;">
                    <div class="lms-progress-percent" style="font-size:44px; font-weight:900; background:linear-gradient(135deg, #00d4ff 0%, #0099ff 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; line-height:1;">{{ $progressPercent }}%</div>
                    <p class="lms-progress-remaining" style="margin:6px 0 0; color:var(--lms-subtle); font-size:12px;">{{ $completedTopics }}/{{ $totalTopics }} completed</p>
                </div>
            </div>
            <div class="lms-progress-track">
                <div class="lms-progress-fill" style="width: {{ max(0, min(100, (int) $progressPercent)) }}%;"></div>
            </div>
            <div style="display:flex; gap:12px; font-size:12px; color:var(--lms-subtle);">
                <span>{{ max(0, $totalTopics - $completedTopics) }} topics remaining</span>
            </div>
        </div>

        <!-- Stats Grid -->
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px; margin-bottom:28px;">
            <!-- Coaching Stats -->
            <div style="border:1px solid var(--lms-border); border-radius:16px; background:var(--lms-card); backdrop-filter:blur(10px); padding:20px; box-shadow:0 4px 16px rgba(0,0,0,0.2);">
                <div style="font-size:24px; margin-bottom:8px;"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#ff6b6b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20m9-9H3"/></svg></div>
                <div style="font-size:13px; color:var(--lms-muted); margin-bottom:6px;">Coaching Sessions</div>
                <div style="font-size:32px; font-weight:900; color:#ff6b6b; margin-bottom:4px;">{{ $upcomingCoachingCount }}</div>
                <p style="margin:0; font-size:12px; color:var(--lms-subtle);">Upcoming session</p>
            </div>

            <!-- Tickets Stats -->
            <div style="border:1px solid var(--lms-border); border-radius:16px; background:var(--lms-card); backdrop-filter:blur(10px); padding:20px; box-shadow:0 4px 16px rgba(0,0,0,0.2);">
                <div style="font-size:24px; margin-bottom:8px;"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#ffd700" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9h12M6 9a3 3 0 0 1 3-3h6a3 3 0 0 1 3 3M6 9v10a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V9M9 5a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1M15 5a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1"/></svg></div>
                <div style="font-size:13px; color:var(--lms-muted); margin-bottom:6px;">Available Tickets</div>
                <div style="font-size:32px; font-weight:900; color:#ffd700; margin-bottom:4px;">{{ $availableTicketCount }}</div>
                <p style="margin:0; font-size:12px; color:var(--lms-subtle);">Coaching tickets</p>
            </div>

            <!-- Topics Stats -->
            <div style="border:1px solid var(--lms-border); border-radius:16px; background:var(--lms-card); backdrop-filter:blur(10px); padding:20px; box-shadow:0 4px 16px rgba(0,0,0,0.2);">
                <div style="font-size:24px; margin-bottom:8px;"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#00d4ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg></div>
                <div style="font-size:13px; color:var(--lms-muted); margin-bottom:6px;">Learning Progress</div>
                <div style="font-size:32px; font-weight:900; color:#00d4ff; margin-bottom:4px;">{{ $completedTopics }}</div>
                <p style="margin:0; font-size:12px; color:var(--lms-subtle);">Topics completed</p>
            </div>
        </div>

        <!-- Action Cards -->
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:20px;">
            <!-- Lessons Card -->
            <a href="{{ $coursesUrl }}" style="text-decoration:none; color:var(--lms-text); border:1px solid var(--lms-border); border-radius:18px; background:linear-gradient(180deg, rgba(0,212,255,0.08) 0%, rgba(0,153,255,0.04) 100%); padding:24px; display:flex; flex-direction:column; justify-content:space-between; min-height:200px; transition:all 0.3s ease; position:relative; overflow:hidden; box-shadow:0 4px 20px rgba(0,212,255,0.1);">
                <div style="position:absolute; right:-30px; top:-30px; width:100px; height:100px; background:rgba(0,212,255,0.05); border-radius:50%; pointer-events:none;"></div>
                <div>
                    <div style="font-size:28px; margin-bottom:12px;"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#00d4ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2zM22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></div>
                    <div style="font-size:20px; font-weight:800; margin-bottom:8px;">Lessons</div>
                    <div style="font-size:13px; color:var(--lms-muted); line-height:1.5;">Continue your lessons based on your latest progress</div>
                </div>
                <div style="display:flex; align-items:center; gap:8px; font-size:12px; color:#00d4ff; font-weight:600;">
                    <span>Start learning</span>
                    <span style="font-size:16px;">→</span>
                </div>
            </a>

            <!-- Coaching Card -->
            <a href="{{ route('coaching.upcoming') }}" style="text-decoration:none; color:var(--lms-text); border:1px solid var(--lms-border); border-radius:18px; background:linear-gradient(180deg, rgba(255,107,107,0.08) 0%, rgba(255,153,0,0.04) 100%); padding:24px; display:flex; flex-direction:column; justify-content:space-between; min-height:200px; transition:all 0.3s ease; position:relative; overflow:hidden; box-shadow:0 4px 20px rgba(255,107,107,0.1);">
                <div style="position:absolute; right:-30px; top:-30px; width:100px; height:100px; background:rgba(255,107,107,0.05); border-radius:50%; pointer-events:none;"></div>
                <div>
                    <div style="font-size:28px; margin-bottom:12px;"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ff6b6b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
                    <div style="font-size:20px; font-weight:800; margin-bottom:8px;">1-on-1 Coaching</div>
                    <div style="font-size:13px; color:var(--lms-muted); line-height:1.5;">Get personal guidance from experienced mentors</div>
                </div>
                <div style="display:flex; align-items:center; gap:8px; font-size:12px; color:#ff6b6b; font-weight:600;">
                    <span>View schedule</span>
                    <span style="font-size:16px;">→</span>
                </div>
            </a>

            @if(auth()->user()->hasIntermediateAccess())
            <a href="{{ route('song.tutorial.index') }}" style="text-decoration:none; color:var(--lms-text); border:1px solid var(--lms-border); border-radius:18px; background:linear-gradient(180deg, rgba(255,215,0,0.08) 0%, rgba(255,165,0,0.04) 100%); padding:24px; display:flex; flex-direction:column; justify-content:space-between; min-height:200px; transition:all 0.3s ease; position:relative; overflow:hidden; box-shadow:0 4px 20px rgba(255,215,0,0.1);">
                <div style="position:absolute; right:-30px; top:-30px; width:100px; height:100px; background:rgba(255,215,0,0.05); border-radius:50%; pointer-events:none;"></div>
                <div>
                    <div style="font-size:28px; margin-bottom:12px;"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ffd700" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 19H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h4m6 0h4a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-4m-6-4h12"/><circle cx="12" cy="12" r="2"/></svg></div>
                    <div style="font-size:20px; font-weight:800; margin-bottom:8px;">Song Tutorial</div>
                    <div style="font-size:13px; color:var(--lms-muted); line-height:1.5;">Explore the song library and playing technique breakdowns</div>
                </div>
                <div style="display:flex; align-items:center; gap:8px; font-size:12px; color:#ffd700; font-weight:600;">
                    <span>Explore</span>
                    <span style="font-size:16px;">→</span>
                </div>
            </a>
            @endif
        </div>
    </div>
</div>

<style>
    a:hover {
        transition: all 0.3s ease;
    }
    [style*="linear-gradient"] { /* cards hover effect */ }
</style>
@endsection
