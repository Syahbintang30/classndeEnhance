@extends('layouts.app')

@section('content')
@php
    $participantName = optional($booking->user)->name ?: 'Participant';
    $sessionLabel = $booking->session_number ?? $booking->id;
@endphp

<div class="vc-root">
    <header class="vc-header">
        <div class="vc-session-info">
            <h1 class="vc-title">
                <span class="vc-avatar">{{ strtoupper(substr($participantName, 0, 1)) }}</span>
                <span>{{ $participantName }}</span>
                <span class="vc-dot">·</span>
                <span>Session {{ $sessionLabel }}</span>
            </h1>

            <div class="vc-meta-row">
                <span class="vc-pill vc-pill-muted">{{ \Carbon\Carbon::parse($booking->booking_time)->format('d M Y — H:i') }}</span>
                <span class="vc-pill vc-pill-ok">{{ ucfirst($booking->status) }}</span>
                <span class="vc-pill vc-pill-timer" id="vc-countdown-timer">
                    <svg class="vc-timer-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" style="margin-right: 4px;">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M12 7v5l3 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span id="vc-countdown-text">1:00:00</span>
                </span>
                @if(($isAdmin ?? false) && !empty($booking->notes))
                    <span class="vc-pill vc-pill-note">Notes: {{ \Illuminate\Support\Str::limit($booking->notes, 90) }}</span>
                @endif
            </div>
        </div>

            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;justify-content:flex-end;">
                <button type="button" id="vc-theme-toggle" class="vc-theme-btn" title="Toggle theme" aria-label="Toggle theme">
                    <svg id="vc-theme-icon-moon" width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <svg id="vc-theme-icon-sun" width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true" style="display:none;">
                        <path d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.36-6.36-1.41 1.41M7.05 16.95l-1.41 1.41m12.72 0-1.41-1.41M7.05 7.05 5.64 5.64M12 8a4 4 0 1 1 0 8 4 4 0 0 1 0-8z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <div class="vc-live-pill">
                    <span class="vc-live-dot"></span>
                    <span>Live</span>
                </div>
        </div>
    </header>

    <main class="vc-main">
        <section class="vc-video-grid">
            <div class="vc-video-card" id="remote-media">
                <div class="vc-empty" id="vc-empty-state">Waiting for the other participant to join...</div>
            </div>

            <div class="vc-video-card vc-local" id="local-media">
                <div class="vc-local-fallback" id="vc-local-fallback">
                    <div class="vc-fallback-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
                    <span>Camera off</span>
                </div>
                <div class="vc-tile-label">You</div>
            </div>
        </section>

        <aside class="vc-sidepanel" id="vc-sidepanel" hidden>
            <div class="vc-sidepanel-head">
                <h2>Session Details</h2>
                <button type="button" id="vc-close-sidepanel" class="vc-close-panel" aria-label="Close panel">&times;</button>
            </div>

            <div class="vc-sidepanel-body">
                <div class="vc-detail-card">
                    <h3>Booking Info</h3>
                    <p><strong>Participant:</strong> {{ $participantName }}</p>
                    <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($booking->booking_time)->format('d M Y H:i') }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($booking->status) }}</p>
                </div>

                @if($isAdmin ?? false)
                    <div class="vc-detail-card">
                        <h3>Notes Admin</h3>
                        <p>{{ $booking->notes ?: '-' }}</p>
                    </div>
                @endif
            </div>
        </aside>

        <aside class="vc-chat-panel" id="vc-chat-panel" hidden>
            <div class="vc-chat-head">
                <h2>Chat</h2>
                <button type="button" id="vc-close-chat" class="vc-close-panel" aria-label="Close chat">&times;</button>
            </div>

            <div class="vc-chat-body" id="vc-chat-messages">
                <div class="vc-chat-welcome">
                    <p>Welcome to the session chat! 💬</p>
                    <p class="vc-chat-welcome-small">Send a message to communicate with the other participant.</p>
                </div>
            </div>

            <div class="vc-chat-footer">
                <form id="vc-chat-form" class="vc-chat-form">
                    <input type="text" id="vc-chat-input" class="vc-chat-input" placeholder="Type a message..." maxlength="500" autocomplete="off">
                    <button type="submit" id="vc-chat-send" class="vc-chat-send" aria-label="Send message">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </form>
            </div>
        </aside>
    </main>

    <footer class="vc-controls-wrap">
        <div class="vc-bottom-left">
            <span id="vc-live-time">--:--</span>
            <span class="vc-divider">|</span>
            <span id="vc-people-count">1 person in the call</span>
        </div>

        <div class="vc-controls">
            <button id="ctl-mic" class="vc-control-btn" title="Toggle microphone" aria-label="Toggle microphone">
                <span class="vc-icon" aria-hidden="true">
                    <svg class="icon-mic-on" width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M12 14a3 3 0 0 0 3-3V7a3 3 0 1 0-6 0v4a3 3 0 0 0 3 3z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M19 11v1a7 7 0 1 1-14 0v-1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 19v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    <svg class="icon-mic-off" width="20" height="20" viewBox="0 0 24 24" fill="none" style="display:none;">
                        <path d="M4 4l16 16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M9 7a3 3 0 0 1 6 0v4a3 3 0 0 1-.5 1.67" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 9v2a3 3 0 0 0 5.02 2.17" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M19 11v1a7 7 0 0 1-11.18 5.66" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 19v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                </span>
            </button>

            <button id="ctl-camera" class="vc-control-btn" title="Toggle camera" aria-label="Toggle camera">
                <span class="vc-icon" aria-hidden="true">
                    <svg class="icon-cam-on" width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <rect x="3" y="7" width="13" height="10" rx="2" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M16 10l5-2v8l-5-2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <svg class="icon-cam-off" width="20" height="20" viewBox="0 0 24 24" fill="none" style="display:none;">
                        <path d="M4 4l16 16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <rect x="3" y="7" width="13" height="10" rx="2" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M16 10l5-2v8l-5-2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </button>

            <button id="ctl-fullscreen" class="vc-control-btn" title="Fullscreen video" aria-label="Fullscreen video">
                <span class="vc-icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M8 3H3v5M16 3h5v5M8 21H3v-5M21 16v5h-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </button>

            <button id="ctl-screen" class="vc-control-btn" title="Share screen" aria-label="Share screen">
                <span class="vc-icon" aria-hidden="true">
                    <svg class="icon-screen-on" width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <rect x="3" y="4" width="18" height="13" rx="2.2" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M8 21h8M12 17v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    <svg class="icon-screen-off" width="20" height="20" viewBox="0 0 24 24" fill="none" style="display:none;">
                        <path d="M4 4l16 16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <rect x="3" y="4" width="18" height="13" rx="2.2" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M8 21h8M12 17v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                </span>
            </button>

            <button id="ctl-detail" class="vc-control-btn vc-desktop-only" title="Open chat" aria-label="Open chat">
                <span class="vc-icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M20 15a2 2 0 0 1-2 2H8l-4 4V5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <span id="vc-chat-badge" class="vc-chat-badge" hidden>0</span>
            </button>

            <button id="hangup" class="vc-control-btn vc-hangup" title="End call" aria-label="End call">
                <span class="vc-icon" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M22 16.92v2a2 2 0 0 1-2.18 2 19.86 19.86 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.86 19.86 0 0 1-3.07-8.67A2 2 0 0 1 4.11 1h2a2 2 0 0 1 2 1.72c.12.9.33 1.78.62 2.62a2 2 0 0 1-.45 2.11L7.1 8.6a16 16 0 0 0 8.3 8.3l1.15-1.18a2 2 0 0 1 2.11-.45c.84.29 1.72.5 2.62.62A2 2 0 0 1 22 16.92z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <span class="vc-hangup-label">End</span>
            </button>
        </div>

        <div class="vc-bottom-right">
            @if($isAdmin ?? false)
                <span class="vc-admin-badge">Admin</span>
            @endif
        </div>
    </footer>
</div>

<div id="vc-modal-backdrop" class="vc-modal-backdrop" aria-hidden="true">
    <div class="vc-modal" role="dialog" aria-modal="true" aria-labelledby="vc-modal-title">
        <h3 id="vc-modal-title">End Session</h3>
        <p id="vc-modal-text">Are you sure you want to end this session?</p>
        <div class="vc-modal-actions">
            <button id="vc-modal-cancel" class="vc-btn">Cancel</button>
            <button id="vc-modal-leave-only" class="vc-btn">Leave only</button>
            <button id="vc-modal-confirm" class="vc-btn vc-btn-danger">End Now</button>
        </div>
    </div>
</div>

<div id="vc-session-ended-backdrop" class="vc-modal-backdrop" aria-hidden="true">
    <div class="vc-modal" role="dialog" aria-modal="true" aria-labelledby="vc-session-ended-title">
        <h3 id="vc-session-ended-title">Meeting Ended</h3>
        <p id="vc-session-ended-text">This coaching session has reached the 1-hour limit. The session will now close for all participants.</p>
        <div class="vc-modal-actions">
            <button id="vc-session-ended-ok" class="vc-btn vc-btn-danger">Understood</button>
        </div>
    </div>
</div>

<div id="vc-session-warning-backdrop" class="vc-modal-backdrop" aria-hidden="true">
    <div class="vc-modal" role="dialog" aria-modal="true" aria-labelledby="vc-session-warning-title">
        <h3 id="vc-session-warning-title">Session Warning</h3>
        <p id="vc-session-warning-text">This coaching session will end in 5 minutes. Please wrap up your discussion.</p>
        <div class="vc-modal-actions">
            <button id="vc-session-warning-ok" class="vc-btn">Understood</button>
        </div>
    </div>
</div>

<div id="vc-floating-notice" class="vc-floating-notice" role="status" aria-live="polite"></div>
<button id="vc-exit-fullscreen" class="vc-exit-fullscreen" type="button" aria-label="Exit fullscreen">Exit Fullscreen</button>
@endsection

@push('styles')
<style>
    .vc-root {
        min-height: calc(100vh - 20px);
        background: radial-gradient(circle at 10% 10%, #1a1c22 0%, #090a0d 45%, #060608 100%);
        color: #eceff4;
        display: flex;
        flex-direction: column;
        padding: 14px;
        gap: 14px;
        font-family: "Manrope", "Segoe UI", sans-serif;
    }

    .vc-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        background: rgba(15, 17, 23, 0.72);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        padding: 14px 18px;
        backdrop-filter: blur(8px);
    }

    .vc-title {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 18px;
        font-weight: 700;
    }

    .vc-avatar {
        width: 32px;
        height: 32px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #4f7cff, #3656cc);
        font-size: 13px;
        box-shadow: 0 8px 24px rgba(79, 124, 255, 0.28);
    }

    .vc-dot {
        opacity: 0.45;
    }

    .vc-meta-row {
        margin-top: 8px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .vc-pill {
        font-size: 12px;
        border-radius: 999px;
        padding: 6px 11px;
        border: 1px solid transparent;
    }

    .vc-pill-muted {
        color: #c8ceda;
        background: rgba(255, 255, 255, 0.07);
        border-color: rgba(255, 255, 255, 0.1);
    }

    .vc-pill-ok {
        color: #80f3b5;
        background: rgba(17, 99, 57, 0.3);
        border-color: rgba(67, 196, 122, 0.35);
    }

    .vc-pill-note {
        color: #9ec6ff;
        background: rgba(27, 52, 92, 0.32);
        border-color: rgba(103, 161, 255, 0.3);
    }

    .vc-pill-timer {
        color: #ffc107;
        background: rgba(97, 80, 14, 0.32);
        border-color: rgba(255, 193, 7, 0.35);
        display: inline-flex;
        align-items: center;
    }

    .vc-timer-icon {
        display: inline-block;
    }

    .vc-pill-timer.vc-timer-warning {
        color: #ffd7d7;
        background: rgba(140, 20, 20, 0.52);
        border-color: rgba(255, 80, 80, 0.68);
    }

    .vc-pill-timer.vc-timer-expired {
        color: #ff6b6b;
        background: rgba(120, 20, 20, 0.4);
        border-color: rgba(255, 60, 60, 0.5);
        animation: vcTimerBlink 1s ease-in-out infinite;
    }

    @keyframes vcTimerBlink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }

    .vc-live-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        color: #ff9f9f;
        border: 1px solid rgba(255, 90, 90, 0.3);
        background: rgba(96, 16, 16, 0.3);
        font-weight: 700;
    }

    .vc-theme-btn {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.03);
        color: rgba(255, 255, 255, 0.7);
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .vc-theme-btn:hover {
        color: #ffffff;
        border-color: rgba(255, 255, 255, 0.6);
        background: rgba(255, 255, 255, 0.08);
    }

    .vc-live-dot {
        width: 9px;
        height: 9px;
        border-radius: 999px;
        background: #ff4747;
        animation: vcPulse 1.2s ease-in-out infinite;
    }

    @keyframes vcPulse {
        0%, 100% { opacity: 0.5; transform: scale(0.92); }
        50% { opacity: 1; transform: scale(1.08); }
    }

    .vc-main {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 14px;
        flex: 1;
        min-height: 0;
    }

    .vc-main.vc-with-panel {
        grid-template-columns: minmax(0, 1fr) 320px;
    }

    .vc-main.vc-with-chat {
        grid-template-columns: minmax(0, 1fr) 320px;
    }

    .vc-main.vc-with-both {
        grid-template-columns: minmax(0, 1fr) 320px 320px;
    }

    .vc-video-grid {
        min-height: 0;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .vc-video-card {
        position: relative;
        border-radius: 18px;
        overflow: hidden;
        min-height: 380px;
        background: linear-gradient(180deg, #11141a 0%, #0b0d11 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.45);
    }

    .vc-video-card video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        background: #07090d;
    }

    #remote-media {
        display: grid;
        grid-template-columns: 1fr;
        gap: 10px;
        padding: 0;
    }

    #remote-media .vc-remote-tile {
        position: relative;
        overflow: hidden;
        border-radius: 18px;
        background: #0c0f14;
        border: 0;
        min-height: 100%;
        height: 100%;
    }

    #remote-media .vc-remote-tile video,
    #local-media video {
        object-fit: cover;
        object-position: center center;
    }

    .vc-tile-label {
        position: absolute;
        left: 12px;
        bottom: 12px;
        z-index: 4;
        border-radius: 999px;
        font-size: 12px;
        background: rgba(0, 0, 0, 0.62);
        border: 1px solid rgba(255, 255, 255, 0.14);
        padding: 6px 10px;
        backdrop-filter: blur(4px);
        pointer-events: none;
    }

    .vc-empty {
        margin: auto;
        color: #98a2b3;
        font-size: 14px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .vc-local video {
        transform: scaleX(-1);
    }

    /* Full-bleed fullscreen mode: fill frame edge-to-edge without side gaps. */
    .vc-video-grid:fullscreen video,
    .vc-video-card:fullscreen video,
    .vc-remote-tile:fullscreen video,
    #local-media:fullscreen video {
        object-fit: cover !important;
        object-position: center center !important;
        transform: none !important;
        background: #06080c;
    }

    #local-media:fullscreen video {
        transform: scaleX(-1) !important;
    }

    .vc-local-fallback {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        color: #9aa4b7;
        background: radial-gradient(circle at 50% 40%, #161b26 0%, #0b0e15 100%);
    }

    .vc-remote-fallback {
        position: absolute;
        inset: 0;
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        color: #9aa4b7;
        background: radial-gradient(circle at 50% 40%, #161b26 0%, #0b0e15 100%);
        z-index: 2;
    }

    .vc-fallback-avatar {
        width: 90px;
        height: 90px;
        border-radius: 999px;
        background: #20273b;
        color: #dbe4ff;
        font-size: 34px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .vc-sidepanel {
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(11, 13, 18, 0.88);
        display: flex;
        flex-direction: column;
        min-height: 0;
    }

    .vc-chat-panel {
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(11, 13, 18, 0.88);
        display: flex;
        flex-direction: column;
        min-height: 0;
    }

    .vc-sidepanel-head {
        padding: 14px 16px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .vc-sidepanel-head h2 {
        margin: 0;
        font-size: 15px;
    }

    .vc-close-panel {
        border: 0;
        background: transparent;
        color: #a3acc0;
        font-size: 26px;
        line-height: 1;
        cursor: pointer;
    }

    .vc-sidepanel-body {
        padding: 14px;
        overflow: auto;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .vc-detail-card {
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        background: #0b0f18;
        padding: 12px;
    }

    .vc-detail-card h3 {
        margin: 0 0 8px;
        color: #c9d4ea;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .vc-detail-card p {
        margin: 6px 0;
        font-size: 13px;
        color: #d2d9e7;
        white-space: pre-wrap;
    }

    .vc-chat-head {
        padding: 14px 16px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .vc-chat-head h2 {
        margin: 0;
        font-size: 15px;
    }

    .vc-chat-body {
        flex: 1;
        padding: 14px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 12px;
        min-height: 0;
    }

    .vc-chat-welcome {
        text-align: center;
        padding: 20px 10px;
        color: #98a2b3;
    }

    .vc-chat-welcome p {
        margin: 0 0 8px;
        font-size: 14px;
        font-weight: 600;
    }

    .vc-chat-welcome-small {
        font-size: 12px !important;
        font-weight: 400 !important;
        margin: 0 !important;
    }

    .vc-chat-message {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .vc-chat-message-header {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 600;
    }

    .vc-chat-message-author {
        color: #c9d4ea;
    }

    .vc-chat-message-time {
        color: #64748b;
        font-weight: 400;
    }

    .vc-chat-message-content {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        padding: 10px 12px;
        font-size: 13px;
        line-height: 1.4;
        color: #e2e8f0;
        word-wrap: break-word;
        white-space: pre-wrap;
    }

    .vc-chat-message.own .vc-chat-message-content {
        background: rgba(79, 124, 255, 0.2);
        color: #dbe4ff;
    }

    .vc-chat-footer {
        padding: 14px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .vc-chat-form {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .vc-chat-input {
        flex: 1;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 8px 16px;
        color: #e2e8f0;
        font-size: 13px;
        outline: none;
        transition: border-color 0.15s ease;
    }

    .vc-chat-input:focus {
        border-color: rgba(79, 124, 255, 0.5);
    }

    .vc-chat-input::placeholder {
        color: #64748b;
    }

    .vc-chat-send {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 1px solid rgba(79, 124, 255, 0.3);
        background: rgba(79, 124, 255, 0.1);
        color: #4f7cff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .vc-chat-send:hover {
        background: rgba(79, 124, 255, 0.2);
        border-color: rgba(79, 124, 255, 0.5);
        color: #5b87ff;
    }

    .vc-chat-send:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

        :root[data-theme="light"] .vc-header,
        :root[data-theme="light"] .vc-video-card,
        :root[data-theme="light"] .vc-sidepanel,
        :root[data-theme="light"] .vc-detail-card,
        :root[data-theme="light"] .vc-control-btn {
            background: #ffffff;
            border-color: rgba(15, 23, 42, 0.08);
            color: #0f172a;
        }

        :root[data-theme="light"] .vc-root {
            background: radial-gradient(circle at 20% 12%, #ffffff 0%, #f8fafc 42%, #eef2ff 100%);
            color: #0f172a;
        }

        :root[data-theme="light"] .vc-title,
        :root[data-theme="light"] .vc-detail-card h3,
        :root[data-theme="light"] .vc-detail-card p,
        :root[data-theme="light"] .vc-bottom-left,
        :root[data-theme="light"] .vc-live-pill,
        :root[data-theme="light"] .vc-pill-muted,
        :root[data-theme="light"] .vc-pill-note,
        :root[data-theme="light"] .vc-pill-ok {
            color: #0f172a;
        }

        :root[data-theme="light"] .vc-pill-muted,
        :root[data-theme="light"] .vc-pill-note,
        :root[data-theme="light"] .vc-pill-ok {
            background: #f8fafc;
            border-color: rgba(15, 23, 42, 0.12);
        }

        :root[data-theme="light"] .vc-pill-timer {
            background: #fef3e2;
            border-color: rgba(255, 165, 0, 0.25);
            color: #b8860b;
        }

        :root[data-theme="light"] .vc-pill-timer.vc-timer-warning {
            background: #ffe4e6;
            border-color: rgba(239, 68, 68, 0.42);
            color: #b91c1c;
        }

        :root[data-theme="light"] .vc-pill-timer.vc-timer-expired {
            background: #fecaca;
            border-color: rgba(220, 38, 38, 0.5);
            color: #991b1b;
        }

        :root[data-theme="light"] .vc-live-pill {
            background: #ffffff;
            border-color: rgba(15, 23, 42, 0.12);
        }

        :root[data-theme="light"] .vc-theme-btn {
            border-color: rgba(15, 23, 42, 0.15);
            color: #334155;
            background: rgba(15, 23, 42, 0.04);
        }

        :root[data-theme="light"] .vc-theme-btn:hover {
            color: #0f172a;
            border-color: rgba(15, 23, 42, 0.28);
            background: rgba(15, 23, 42, 0.08);
        }

        :root[data-theme="light"] .vc-chat-panel,
        :root[data-theme="light"] .vc-sidepanel {
            background: rgba(255, 255, 255, 0.95);
            border-color: rgba(15, 23, 42, 0.08);
        }

        :root[data-theme="light"] .vc-chat-head,
        :root[data-theme="light"] .vc-sidepanel-head {
            border-color: rgba(15, 23, 42, 0.08);
        }

        :root[data-theme="light"] .vc-chat-head h2,
        :root[data-theme="light"] .vc-sidepanel-head h2 {
            color: #0f172a;
        }

        :root[data-theme="light"] .vc-close-panel {
            color: #64748b;
        }

        :root[data-theme="light"] .vc-chat-welcome {
            color: #64748b;
        }

        :root[data-theme="light"] .vc-chat-message-author {
            color: #334155;
        }

        :root[data-theme="light"] .vc-chat-message-time {
            color: #94a3b8;
        }

        :root[data-theme="light"] .vc-chat-message-content {
            background: rgba(15, 23, 42, 0.05);
            color: #334155;
        }

        :root[data-theme="light"] .vc-chat-message.own .vc-chat-message-content {
            background: rgba(79, 124, 255, 0.1);
            color: #1e40af;
        }

        :root[data-theme="light"] .vc-chat-input {
            background: rgba(15, 23, 42, 0.05);
            border-color: rgba(15, 23, 42, 0.1);
            color: #334155;
        }

        :root[data-theme="light"] .vc-chat-input:focus {
            border-color: rgba(79, 124, 255, 0.5);
        }

        :root[data-theme="light"] .vc-chat-input::placeholder {
            color: #94a3b8;
        }

        :root[data-theme="light"] .vc-chat-footer {
            border-color: rgba(15, 23, 42, 0.08);
        }

        :root[data-theme="light"] .vc-avatar {
            box-shadow: none;
        }

        :root[data-theme="light"] .vc-empty,
        :root[data-theme="light"] .vc-local-fallback,
        :root[data-theme="light"] .vc-fallback-avatar,
        :root[data-theme="light"] .vc-remote-fallback {
            color: #0f172a;
        }

        :root[data-theme="light"] .vc-local-fallback,
        :root[data-theme="light"] .vc-remote-fallback {
            background: radial-gradient(circle at 50% 40%, #f8fbff 0%, #e6eefc 100%);
        }

        :root[data-theme="light"] .vc-video-card {
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
        }

        :root[data-theme="light"] .vc-tile-label {
            background: rgba(255, 255, 255, 0.8);
            color: #0f172a;
            border-color: rgba(15, 23, 42, 0.16);
        }

        :root[data-theme="light"] .vc-control-btn:hover {
            border-color: rgba(15, 23, 42, 0.16);
        }

        :root[data-theme="light"] .vc-theme-toggle-label {
            color: #0f172a;
        }

    .vc-controls-wrap {
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(9, 10, 14, 0.94);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 10px 14px;
    }

    .vc-controls {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .vc-control-btn {
        width: 48px;
        height: 48px;
        position: relative;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        background: #1b1f2a;
        color: #f1f5ff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform .15s ease, background .15s ease, border-color .15s ease;
    }

    .vc-control-btn .vc-icon svg {
        display: block;
    }

    .vc-chat-badge {
        position: absolute;
        top: -2px;
        right: -2px;
        min-width: 18px;
        height: 18px;
        border-radius: 999px;
        background: #ef4444;
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.35);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 5px;
        font-size: 10px;
        font-weight: 800;
        line-height: 1;
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.45);
        pointer-events: none;
        z-index: 2;
    }

    .vc-video-card .vc-fs-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 34px;
        height: 34px;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.16);
        background: rgba(12, 14, 20, 0.55);
        color: #eaf1ff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        backdrop-filter: blur(4px);
        z-index: 3;
    }

    .vc-video-card .vc-fs-btn:hover {
        background: rgba(25, 30, 40, 0.8);
    }

    .vc-control-btn:hover {
        transform: translateY(-2px);
        background: #242a38;
    }

    .vc-control-btn.is-muted {
        background: rgba(125, 28, 28, 0.28);
        border-color: rgba(255, 103, 103, 0.45);
        color: #ff9f9f;
    }

    .vc-hangup {
        width: auto;
        padding: 0 16px;
        gap: 8px;
        background: #d63939;
        border-color: #de5252;
        color: #fff;
    }

    .vc-hangup:hover {
        background: #ec4242;
    }

    .vc-hangup-label {
        font-size: 13px;
        font-weight: 700;
    }

    .vc-bottom-left,
    .vc-bottom-right {
        font-size: 13px;
        color: #9aa7bd;
        min-width: 140px;
    }

    .vc-divider {
        margin: 0 8px;
        opacity: 0.5;
    }

    .vc-admin-badge {
        background: rgba(39, 93, 213, 0.33);
        color: #b9d5ff;
        border: 1px solid rgba(101, 157, 255, 0.45);
        border-radius: 999px;
        padding: 5px 10px;
        font-size: 11px;
        font-weight: 700;
    }

    .vc-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.65);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 2000;
    }

    .vc-modal {
        width: min(420px, 94vw);
        border-radius: 14px;
        border: 1px solid rgba(255, 255, 255, 0.15);
        background: #10141d;
        color: #ebeff8;
        padding: 18px;
    }

    .vc-modal h3 {
        margin: 0 0 8px;
    }

    .vc-modal p {
        margin: 0;
        color: #a8b3c8;
    }

    .vc-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 16px;
    }

    .vc-btn {
        border: 1px solid rgba(255, 255, 255, 0.15);
        background: #1b2231;
        color: #f5f7fb;
        border-radius: 10px;
        padding: 8px 12px;
        cursor: pointer;
    }

    .vc-btn-danger {
        background: #d63636;
        border-color: #e45353;
    }

    .vc-floating-notice {
        position: fixed;
        top: 18px;
        left: 50%;
        transform: translateX(-50%) translateY(-16px);
        min-width: min(560px, 94vw);
        max-width: 94vw;
        border-radius: 12px;
        border: 1px solid rgba(255, 173, 103, 0.45);
        background: rgba(59, 36, 14, 0.96);
        color: #ffd9aa;
        padding: 11px 14px;
        z-index: 2200;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.46);
        opacity: 0;
        pointer-events: none;
        transition: opacity .2s ease, transform .2s ease;
        text-align: center;
        font-size: 13px;
        font-weight: 600;
    }

    .vc-floating-notice.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }

    .vc-control-btn.is-active {
        background: #2a3852;
        border-color: rgba(128, 176, 255, 0.65);
        color: #d9e9ff;
    }

    .vc-exit-fullscreen {
        position: fixed;
        top: 16px;
        right: 16px;
        z-index: 2500;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(8, 10, 16, 0.86);
        color: #e9effa;
        border-radius: 999px;
        padding: 7px 12px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.2px;
        display: none;
        cursor: pointer;
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.34);
    }

    .vc-exit-fullscreen.show {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    @media (max-width: 1024px) {
        .vc-video-grid {
            grid-template-columns: 1fr;
        }

        .vc-video-card {
            min-height: 280px;
        }

        .vc-main.vc-with-panel {
            grid-template-columns: 1fr;
        }

        .vc-sidepanel {
            max-height: 300px;
        }
    }

    @media (max-width: 700px) {
        .vc-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .vc-controls-wrap {
            flex-direction: column;
            align-items: stretch;
        }

        .vc-controls {
            justify-content: center;
        }

        .vc-bottom-left,
        .vc-bottom-right {
            min-width: 0;
            text-align: center;
        }

        .vc-desktop-only {
            display: none;
        }
    }
</style>
@endpush

@push('scripts')
@if (file_exists(public_path('js/twilio-video.min.js')))
<script src="{{ asset('js/twilio-video.min.js') }}"></script>
@endif
<script>
(function () {
    const token = {!! json_encode($accessToken ?? null) !!};
    const roomName = {!! json_encode($roomName ?? null) !!};
    const isAdmin = @json((bool) ($isAdmin ?? false));
    const bookingId = {{ (int) $booking->id }};
    const endRoomUrl = {!! json_encode(url('/admin/coaching/bookings/' . $booking->id . '/end-room')) !!};
    const eventUrl = {!! json_encode(url('/coaching/' . $booking->id . '/event')) !!};
    const leaveUrl = {!! json_encode(route('coaching.index')) !!};
    const csrfToken = {!! json_encode(csrf_token()) !!};
    const bookingTime = new Date({!! json_encode($booking->booking_time->getTimestamp() * 1000) !!});
    const currentUserName = {!! json_encode(auth()->user()->name ?? 'You') !!};

    const localMedia = document.getElementById('local-media');
    const remoteMedia = document.getElementById('remote-media');
    const emptyState = document.getElementById('vc-empty-state');
    const localFallback = document.getElementById('vc-local-fallback');

    const btnMic = document.getElementById('ctl-mic');
    const btnCam = document.getElementById('ctl-camera');
    const btnDetail = document.getElementById('ctl-detail');
    const chatBadge = document.getElementById('vc-chat-badge');
    const btnFullscreen = document.getElementById('ctl-fullscreen');
    const btnScreen = document.getElementById('ctl-screen');
    const btnHangup = document.getElementById('hangup');

    const peopleCount = document.getElementById('vc-people-count');
    const liveTime = document.getElementById('vc-live-time');
    const main = document.querySelector('.vc-main');
    const sidepanel = document.getElementById('vc-sidepanel');
    const closePanel = document.getElementById('vc-close-sidepanel');
    const chatPanel = document.getElementById('vc-chat-panel');
    const closeChat = document.getElementById('vc-close-chat');
    const chatMessages = document.getElementById('vc-chat-messages');
    const chatForm = document.getElementById('vc-chat-form');
    const chatInput = document.getElementById('vc-chat-input');

    const modal = document.getElementById('vc-modal-backdrop');
    const modalText = document.getElementById('vc-modal-text');
    const modalCancel = document.getElementById('vc-modal-cancel');
    const modalLeaveOnly = document.getElementById('vc-modal-leave-only');
    const modalConfirm = document.getElementById('vc-modal-confirm');
    const sessionWarningModal = document.getElementById('vc-session-warning-backdrop');
    const sessionWarningOk = document.getElementById('vc-session-warning-ok');
    const sessionEndedModal = document.getElementById('vc-session-ended-backdrop');
    const sessionEndedOk = document.getElementById('vc-session-ended-ok');
    const floatingNotice = document.getElementById('vc-floating-notice');
    const exitFullscreenBtn = document.getElementById('vc-exit-fullscreen');
    const themeToggle = document.getElementById('vc-theme-toggle');
    const themeIconMoon = document.getElementById('vc-theme-icon-moon');
    const themeIconSun = document.getElementById('vc-theme-icon-sun');

    let room = null;
    let localVideoTrack = null;
    let localAudioTrack = null;
    let localChatDataTrack = null;
    let localScreenTrack = null;
    let isScreenSharing = false;
    let selfHangup = false;
    let unreadChatCount = 0;
    let sessionDurationMinutes = 60; // Default 1 hour
    let sessionFiveMinuteWarningShown = false;
    let sessionEndTimeNotificationShown = false;
    let sessionAutoEnded = false;
    let sessionWarningHideTimer = null;
    let countdownTimerElement = document.getElementById('vc-countdown-timer');
    let countdownTextElement = document.getElementById('vc-countdown-text');

    function log(msg) {
        try { console.log('[coaching.session]', msg); } catch (e) {}
    }

    function syncThemeToggle() {
        const theme = document.documentElement.getAttribute('data-theme') || 'dark';
        if (themeIconMoon && themeIconSun) {
            const isLight = theme === 'light';
            themeIconMoon.style.display = isLight ? 'none' : 'block';
            themeIconSun.style.display = isLight ? 'block' : 'none';
        }
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            const nextTheme = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', nextTheme);
            document.cookie = 'theme=' + nextTheme + '; path=/; max-age=' + (60 * 60 * 24 * 365);
            syncThemeToggle();
        });
    }

    syncThemeToggle();

    function setClock() {
        const now = new Date();
        const hh = String(now.getHours()).padStart(2, '0');
        const mm = String(now.getMinutes()).padStart(2, '0');
        if (liveTime) liveTime.textContent = hh + ':' + mm;
    }

    function formatTimeRemaining(seconds) {
        if (seconds < 0) seconds = 0;
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        return String(hours).padStart(1, '0') + ':' + 
               String(minutes).padStart(2, '0') + ':' + 
               String(secs).padStart(2, '0');
    }

    function updateCountdownTimer() {
        if (!countdownTextElement || !bookingTime || sessionAutoEnded) return;

        const now = new Date();
        const sessionEndTime = new Date(bookingTime.getTime() + (sessionDurationMinutes * 60 * 1000));
        const remainingMs = sessionEndTime - now;
        const remainingSeconds = Math.max(0, Math.floor(remainingMs / 1000));

        // Update display
        countdownTextElement.textContent = formatTimeRemaining(remainingSeconds);

        // Update styling based on time remaining
        if (countdownTimerElement) {
            countdownTimerElement.classList.remove('vc-timer-warning', 'vc-timer-expired');
            
            if (remainingSeconds === 0) {
                countdownTimerElement.classList.add('vc-timer-expired');
            } else if (remainingSeconds <= 300) { // Last 5 minutes
                countdownTimerElement.classList.add('vc-timer-warning');
            } else if (remainingSeconds <= 600) { // Last 10 minutes
                countdownTimerElement.classList.add('vc-timer-warning');
            }
        }

        // Show notification when session time expires
        if (remainingSeconds <= 300 && remainingSeconds > 0 && !sessionFiveMinuteWarningShown) {
            sessionFiveMinuteWarningShown = true;
            showSessionFiveMinuteWarning();
        }

        // Ensure hard auto-end at 0 second.
        if (remainingSeconds === 0 && !sessionEndTimeNotificationShown) {
            sessionEndTimeNotificationShown = true;
            handleSessionAutoEnd();
        }
    }

    function updatePeople() {
        const total = 1 + (room ? room.participants.size : 0);
        if (peopleCount) {
            peopleCount.textContent = total + ' people in the call';
        }
    }

    function escapeHtml(value) {
        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function formatChatTime(ts) {
        const date = ts ? new Date(ts) : new Date();
        if (Number.isNaN(date.getTime())) return '--:--';
        const hh = String(date.getHours()).padStart(2, '0');
        const mm = String(date.getMinutes()).padStart(2, '0');
        return hh + ':' + mm;
    }

    function clearChatWelcome() {
        if (!chatMessages) return;
        const welcome = chatMessages.querySelector('.vc-chat-welcome');
        if (welcome) welcome.remove();
    }

    function appendChatMessage(author, message, ownMessage, timestamp) {
        if (!chatMessages || !message) return;
        clearChatWelcome();
        const item = document.createElement('div');
        item.className = 'vc-chat-message' + (ownMessage ? ' own' : '');
        item.innerHTML =
            '<div class="vc-chat-message-header">' +
                '<span class="vc-chat-message-author">' + escapeHtml(author || 'Participant') + '</span>' +
                '<span class="vc-chat-message-time">' + escapeHtml(formatChatTime(timestamp)) + '</span>' +
            '</div>' +
            '<div class="vc-chat-message-content">' + escapeHtml(message) + '</div>';
        chatMessages.appendChild(item);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function setUnreadChatCount(count) {
        unreadChatCount = Math.max(0, count || 0);
        if (!chatBadge) return;
        if (unreadChatCount > 0) {
            chatBadge.hidden = false;
            chatBadge.textContent = unreadChatCount > 99 ? '99+' : String(unreadChatCount);
            btnDetail && btnDetail.setAttribute('aria-label', 'Open chat, ' + unreadChatCount + ' pesan baru');
            return;
        }
        chatBadge.hidden = true;
        chatBadge.textContent = '0';
        btnDetail && btnDetail.setAttribute('aria-label', 'Open chat');
    }

    function toggleChatPanel(forceOpen) {
        if (!chatPanel || !main || !btnDetail) return;
        const open = typeof forceOpen === 'boolean' ? forceOpen : chatPanel.hidden;
        chatPanel.hidden = !open;
        main.classList.toggle('vc-with-chat', open);
        btnDetail.classList.toggle('is-active', open);
        if (open) setUnreadChatCount(0);
        if (open && chatInput) {
            setTimeout(function () { chatInput.focus(); }, 0);
        }
    }

    function handleIncomingChatMessage(raw, fallbackAuthor) {
        let payload = null;
        try {
            payload = JSON.parse(raw);
        } catch (_) {
            payload = null;
        }

        if (payload && payload.type === 'chat' && payload.message) {
            appendChatMessage(payload.sender || fallbackAuthor || 'Participant', payload.message, false, payload.sentAt);
            const isChatPanelOpen = chatPanel && chatPanel.hidden === false;
            if (!isChatPanelOpen) {
                setUnreadChatCount(unreadChatCount + 1);
            }
            return;
        }

        if (typeof raw === 'string' && raw.trim()) {
            appendChatMessage(fallbackAuthor || 'Participant', raw.trim(), false, Date.now());
            const isChatPanelOpen = chatPanel && chatPanel.hidden === false;
            if (!isChatPanelOpen) {
                setUnreadChatCount(unreadChatCount + 1);
            }
        }
    }

    function bindParticipantDataTrack(track, participant) {
        if (!track || track.kind !== 'data') return;
        track.on('message', function (raw) {
            handleIncomingChatMessage(raw, participant && participant.identity ? participant.identity : 'Participant');
        });
    }

    function refreshEmptyState() {
        if (!emptyState) return;
        const count = remoteMedia ? remoteMedia.querySelectorAll('.vc-remote-tile').length : 0;
        emptyState.style.display = count > 0 ? 'none' : 'flex';
    }

    function setMicMuted(muted) {
        if (!btnMic) return;
        btnMic.classList.toggle('is-muted', muted);
        const onIcon = btnMic.querySelector('.icon-mic-on');
        const offIcon = btnMic.querySelector('.icon-mic-off');
        if (onIcon && offIcon) {
            onIcon.style.display = muted ? 'none' : 'block';
            offIcon.style.display = muted ? 'block' : 'none';
        }
    }

    function setCameraMuted(muted) {
        if (!btnCam) return;
        btnCam.classList.toggle('is-muted', muted);
        const onIcon = btnCam.querySelector('.icon-cam-on');
        const offIcon = btnCam.querySelector('.icon-cam-off');
        if (onIcon && offIcon) {
            onIcon.style.display = muted ? 'none' : 'block';
            offIcon.style.display = muted ? 'block' : 'none';
        }
        if (localFallback) localFallback.style.display = muted ? 'flex' : 'none';
        const localVideo = localMedia ? localMedia.querySelector('video') : null;
        if (localVideo) localVideo.style.display = muted ? 'none' : 'block';
    }

    function setScreenSharing(active) {
        isScreenSharing = !!active;
        if (!btnScreen) return;
        btnScreen.classList.toggle('is-active', isScreenSharing);
        const onIcon = btnScreen.querySelector('.icon-screen-on');
        const offIcon = btnScreen.querySelector('.icon-screen-off');
        if (onIcon && offIcon) {
            onIcon.style.display = isScreenSharing ? 'none' : 'block';
            offIcon.style.display = isScreenSharing ? 'block' : 'none';
        }
    }

    function clearLocalPreviewVideo() {
        if (!localMedia) return;
        localMedia.querySelectorAll('video').forEach(function (el) { el.remove(); });
    }

    function attachLocalPreviewTrack(track, mirror) {
        if (!localMedia || !track || typeof track.attach !== 'function') return;
        clearLocalPreviewVideo();
        const el = track.attach();
        el.style.transform = mirror ? 'scaleX(-1)' : 'none';
        localMedia.insertBefore(el, localMedia.firstChild);
        if (localFallback) localFallback.style.display = 'none';
    }

    async function stopScreenShare() {
        if (!isScreenSharing || !room) return;
        try {
            if (localScreenTrack) {
                room.localParticipant.unpublishTrack(localScreenTrack);
                localScreenTrack.stop();
            }
        } catch (e) {}

        localScreenTrack = null;
        setScreenSharing(false);

        if (localVideoTrack) {
            try {
                room.localParticipant.publishTrack(localVideoTrack);
            } catch (e) {}
            if (localVideoTrack.isEnabled) {
                attachLocalPreviewTrack(localVideoTrack, true);
                setCameraMuted(false);
            } else {
                clearLocalPreviewVideo();
                setCameraMuted(true);
            }
        }
    }

    async function startScreenShare() {
        if (!room || !navigator.mediaDevices || !navigator.mediaDevices.getDisplayMedia) {
            showFloatingNotice('Share screen tidak didukung di browser ini.');
            return;
        }
        try {
            const stream = await navigator.mediaDevices.getDisplayMedia({ video: true, audio: false });
            const mediaTrack = stream.getVideoTracks()[0];
            if (!mediaTrack) {
                showFloatingNotice('Tidak ada layar yang dibagikan.');
                return;
            }

            const { LocalVideoTrack } = window.Twilio.Video;
            localScreenTrack = new LocalVideoTrack(mediaTrack, { name: 'screen' });

            if (localVideoTrack) {
                room.localParticipant.unpublishTrack(localVideoTrack);
            }

            await room.localParticipant.publishTrack(localScreenTrack);
            attachLocalPreviewTrack(localScreenTrack, false);
            setScreenSharing(true);

            mediaTrack.addEventListener('ended', function () {
                stopScreenShare();
            }, { once: true });
        } catch (e) {
            if (e && e.name === 'NotAllowedError') return;
            showFloatingNotice('Failed to start screen sharing. Please try again.');
        }
    }

    function createFullscreenButton(target, label) {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'vc-fs-btn';
        button.setAttribute('aria-label', 'Fullscreen ' + label);
        button.title = 'Fullscreen';
        button.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M8 3H3v5M16 3h5v5M8 21H3v-5M21 16v5h-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>';
        button.addEventListener('click', function (e) {
            e.stopPropagation();
            toggleFullscreen(target);
        });
        target.appendChild(button);
    }

    function toggleFullscreen(el) {
        if (!el) return;
        if (document.fullscreenElement) {
            document.exitFullscreen && document.exitFullscreen();
            return;
        }
        if (el.requestFullscreen) {
            el.requestFullscreen().catch(() => {
                showFloatingNotice('Fullscreen tidak didukung di browser ini.');
            });
        }
    }

    function updateFullscreenUi() {
        const isFullscreen = !!document.fullscreenElement;
        if (btnFullscreen) {
            btnFullscreen.classList.toggle('is-active', isFullscreen);
        }
        if (exitFullscreenBtn) {
            exitFullscreenBtn.classList.toggle('show', isFullscreen);
        }
    }

    function createRemoteFallback(participant) {
        const fallback = document.createElement('div');
        fallback.className = 'vc-remote-fallback';
        const name = (participant && participant.identity) ? String(participant.identity) : 'Participant';
        const initial = name.trim().charAt(0).toUpperCase() || 'P';
        fallback.innerHTML =
            '<div class="vc-fallback-avatar">' + escapeHtml(initial) + '</div>' +
            '<span>Camera off</span>';
        return fallback;
    }

    function setRemoteTrackMuted(tile, muted) {
        if (!tile) return;
        const fallback = tile.querySelector('.vc-remote-fallback');
        if (fallback) fallback.style.display = muted ? 'flex' : 'none';
        const remoteVideos = tile.querySelectorAll('video');
        remoteVideos.forEach(function (videoEl) {
            videoEl.style.display = muted ? 'none' : 'block';
        });
    }

    function bindRemoteTrackState(track, tile) {
        if (!track || track.kind !== 'video') return;
        track.on('disabled', function () { setRemoteTrackMuted(tile, true); });
        track.on('enabled', function () { setRemoteTrackMuted(tile, false); });
        setRemoteTrackMuted(tile, track.isEnabled === false);
    }

    function addRemoteParticipant(participant) {
        if (!remoteMedia) return;

        const tile = document.createElement('div');
        tile.className = 'vc-remote-tile';
        tile.id = 'remote-' + participant.sid;
        createFullscreenButton(tile, participant.identity || 'participant');

        const label = document.createElement('div');
        label.className = 'vc-tile-label';
        label.textContent = participant.identity || 'Participant';
        tile.appendChild(label);
        tile.appendChild(createRemoteFallback(participant));

        participant.tracks.forEach(pub => {
            if (pub.track && pub.track.kind !== 'data') {
                tile.appendChild(pub.track.attach());
                bindRemoteTrackState(pub.track, tile);
            }
        });

        participant.on('trackSubscribed', track => {
            if (track.kind === 'data') {
                bindParticipantDataTrack(track, participant);
                return;
            }
            tile.appendChild(track.attach());
            bindRemoteTrackState(track, tile);
        });

        participant.dataTracks.forEach(pub => {
            if (pub.track) bindParticipantDataTrack(pub.track, participant);
        });

        participant.on('trackUnsubscribed', track => {
            if (track.kind === 'data') return;
            track.detach().forEach(el => el.remove());
            if (track.kind === 'video') setRemoteTrackMuted(tile, true);
        });

        remoteMedia.appendChild(tile);
        refreshEmptyState();
    }

    function removeRemoteParticipant(participant) {
        const el = document.getElementById('remote-' + participant.sid);
        if (el) el.remove();
        refreshEmptyState();
    }

    async function endSessionByAdmin() {
        try {
            const res = await fetch(endRoomUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            let json = null;
            try {
                json = await res.json();
            } catch (_) {
                json = null;
            }

            if (!res.ok || !(json && json.success)) {
                const msg = (json && json.error) ? json.error : 'Failed to end the session for all participants.';
                showFloatingNotice(msg);
                return false;
            }

            return true;
        } catch (e) {
            log('endRoom request failed: ' + (e && e.message ? e.message : e));
            showFloatingNotice('Failed to end the session. Check your connection and try again.');
            return false;
        }
    }

    async function sendEvent(eventName, meta) {
        try {
            await fetch(eventUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify({ event: eventName, meta: meta || {} })
            });
        } catch (e) {
            log('event log failed: ' + (e && e.message ? e.message : e));
        }
    }

    function disconnectAndLeave() {
        try {
            if (room && room.state === 'connected') room.disconnect();
        } catch (e) {}

        try {
            if (localVideoTrack) localVideoTrack.stop();
            if (localAudioTrack) localAudioTrack.stop();
        } catch (e) {}

        window.location.href = leaveUrl;
    }

    function openEndModal(message) {
        if (modalText && message) modalText.textContent = message;
        if (modalLeaveOnly) modalLeaveOnly.style.display = isAdmin ? '' : 'none';
        if (modalConfirm) modalConfirm.textContent = isAdmin ? 'End for Everyone' : 'End Now';
        if (modal) modal.style.display = 'flex';
    }

    function closeEndModal() {
        if (modal) modal.style.display = 'none';
    }

    function showFloatingNotice(message) {
        if (!floatingNotice || !message) return;
        floatingNotice.textContent = message;
        floatingNotice.classList.add('show');
    }

    function disableSessionControls() {
        [btnMic, btnCam, btnDetail, btnFullscreen, btnScreen, btnHangup, chatInput].forEach(function (el) {
            if (el) el.disabled = true;
        });
    }

    function showSessionEndedModal() {
        if (!sessionEndedModal) return;
        sessionEndedModal.style.display = 'flex';
    }

    async function showSessionFiveMinuteWarning() {
        await sendEvent('session_warning_five_minutes', {
            booking_id: bookingId,
            duration_minutes: sessionDurationMinutes
        });
        showFloatingNotice('The session will end in 5 minutes.');
        if (sessionWarningModal) {
            sessionWarningModal.style.display = 'flex';
            if (sessionWarningHideTimer) {
                clearTimeout(sessionWarningHideTimer);
            }
            sessionWarningHideTimer = setTimeout(function () {
                sessionWarningModal.style.display = 'none';
                sessionWarningHideTimer = null;
            }, 5000);
        }
    }

    async function handleSessionAutoEnd() {
        if (sessionAutoEnded) return;
        sessionAutoEnded = true;
        selfHangup = true;
        closeEndModal();
        disableSessionControls();

        await sendEvent('session_time_expired', {
            booking_id: bookingId,
            duration_minutes: sessionDurationMinutes
        });

        // Admin closes room at source; participants still enforce local disconnect.
        if (isAdmin) {
            await endSessionByAdmin();
        }

        showSessionEndedModal();

        setTimeout(function () {
            disconnectAndLeave();
        }, 3500);
    }

    async function connectRoom() {
        if (!window.Twilio || !window.Twilio.Video) {
            throw new Error('Twilio SDK tidak tersedia');
        }

        if (!token || !roomName) {
            throw new Error('Token atau room tidak tersedia');
        }

        const { connect, createLocalVideoTrack, createLocalAudioTrack, LocalDataTrack } = window.Twilio.Video;

        try {
            localVideoTrack = await createLocalVideoTrack();
            if (localMedia) {
                const el = localVideoTrack.attach();
                localMedia.insertBefore(el, localMedia.firstChild);
                if (localFallback) localFallback.style.display = 'none';
            }
            setCameraMuted(false);
        } catch (e) {
            setCameraMuted(true);
        }

        try {
            localAudioTrack = await createLocalAudioTrack();
            setMicMuted(false);
        } catch (e) {
            setMicMuted(true);
        }

        try {
            localChatDataTrack = new LocalDataTrack();
        } catch (e) {
            localChatDataTrack = null;
        }

        const tracks = [localVideoTrack, localAudioTrack, localChatDataTrack].filter(Boolean);
        room = await connect(token, { name: roomName, tracks: tracks });

        room.participants.forEach(addRemoteParticipant);
        room.on('participantConnected', participant => {
            addRemoteParticipant(participant);
            updatePeople();
        });
        room.on('participantDisconnected', participant => {
            removeRemoteParticipant(participant);
            updatePeople();
        });

        room.on('disconnected', async function (_, error) {
            if (selfHangup) return;

            const reason = (error && error.message) ? error.message.toLowerCase() : '';
            const endedByAdmin = reason.includes('completed') || reason.includes('ended') || reason.includes('room');

            if (!isAdmin && endedByAdmin) {
                await sendEvent('session_ended_by_admin', { booking_id: bookingId });
                showFloatingNotice('The session was ended by the admin. You will be redirected...');
                setTimeout(function () {
                    window.location.href = leaveUrl;
                }, 1500);
                return;
            }

            window.location.href = leaveUrl;
        });

        updatePeople();
        refreshEmptyState();
    }

    async function init() {
        setClock();
        setInterval(setClock, 1000);
        
        // Update countdown timer every second
        setInterval(updateCountdownTimer, 1000);

        if (btnDetail && chatPanel) {
            btnDetail.addEventListener('click', function () {
                toggleChatPanel();
            });
        }

        if (closeChat && chatPanel) {
            closeChat.addEventListener('click', function () {
                toggleChatPanel(false);
            });
        }

        if (closePanel && sidepanel) {
            closePanel.addEventListener('click', function () {
                sidepanel.hidden = true;
                main.classList.remove('vc-with-panel');
                if (btnDetail) btnDetail.classList.remove('is-active');
            });
        }

        if (chatForm && chatInput) {
            chatForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const message = (chatInput.value || '').trim();
                if (!message || !localChatDataTrack) return;

                try {
                    localChatDataTrack.send(JSON.stringify({
                        type: 'chat',
                        sender: currentUserName,
                        message: message,
                        sentAt: Date.now()
                    }));
                    appendChatMessage(currentUserName, message, true, Date.now());
                    chatInput.value = '';
                } catch (_) {
                    showFloatingNotice('Failed to send chat message. Please try again.');
                }
            });
        }

        if (btnMic) {
            btnMic.addEventListener('click', function () {
                if (!localAudioTrack) return;
                const enabled = localAudioTrack.isEnabled;
                localAudioTrack.enable(!enabled);
                setMicMuted(enabled);
            });
        }

        if (btnCam) {
            btnCam.addEventListener('click', function () {
                if (isScreenSharing) {
                    showFloatingNotice('Matikan share screen dulu untuk mengubah kamera.');
                    return;
                }
                if (!localVideoTrack) return;
                const enabled = localVideoTrack.isEnabled;
                localVideoTrack.enable(!enabled);
                setCameraMuted(enabled);
            });
        }

        if (btnScreen) {
            btnScreen.addEventListener('click', function () {
                if (isScreenSharing) {
                    stopScreenShare();
                    return;
                }
                startScreenShare();
            });
        }

        if (btnHangup) {
            btnHangup.addEventListener('click', function () {
                const message = isAdmin
                    ? 'End the session for all participants? The user will also be removed from the room immediately.'
                    : 'Are you sure you want to leave this session?';
                openEndModal(message);
            });
        }

        if (modalCancel) modalCancel.addEventListener('click', closeEndModal);
        if (modalLeaveOnly) {
            modalLeaveOnly.addEventListener('click', function () {
                selfHangup = true;
                closeEndModal();
                disconnectAndLeave();
            });
        }
        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === modal) closeEndModal();
            });
        }

        if (sessionEndedModal) {
            sessionEndedModal.addEventListener('click', function (e) {
                if (e.target === sessionEndedModal) disconnectAndLeave();
            });
        }

        if (sessionEndedOk) {
            sessionEndedOk.addEventListener('click', function () {
                disconnectAndLeave();
            });
        }

        if (sessionWarningModal) {
            sessionWarningModal.addEventListener('click', function (e) {
                if (e.target === sessionWarningModal) {
                    sessionWarningModal.style.display = 'none';
                }
            });
        }

        if (sessionWarningOk) {
            sessionWarningOk.addEventListener('click', function () {
                if (sessionWarningModal) sessionWarningModal.style.display = 'none';
            });
        }

        if (modalConfirm) {
            modalConfirm.addEventListener('click', async function () {
                selfHangup = true;
                modalConfirm.disabled = true;

                await sendEvent('session_end_clicked', {
                    by_admin: isAdmin,
                    booking_id: bookingId
                });

                if (isAdmin) {
                    const endedAll = await endSessionByAdmin();
                    if (!endedAll) {
                        selfHangup = false;
                        modalConfirm.disabled = false;
                        return;
                    }
                }

                disconnectAndLeave();
            });
        }

        if (localMedia) {
            createFullscreenButton(localMedia, 'local video');
            localMedia.addEventListener('dblclick', function () {
                toggleFullscreen(localMedia);
            });
        }

        if (remoteMedia) {
            remoteMedia.addEventListener('dblclick', function (e) {
                const tile = e.target.closest('.vc-remote-tile');
                if (tile) toggleFullscreen(tile);
            });
        }

        if (btnFullscreen) {
            btnFullscreen.addEventListener('click', function () {
                toggleFullscreen(document.querySelector('.vc-video-grid'));
            });
        }

        if (exitFullscreenBtn) {
            exitFullscreenBtn.addEventListener('click', function () {
                if (document.fullscreenElement && document.exitFullscreen) {
                    document.exitFullscreen();
                }
            });
        }

        document.addEventListener('fullscreenchange', updateFullscreenUi);
        updateFullscreenUi();

        try {
            await connectRoom();
        } catch (e) {
            log(e && e.message ? e.message : e);
            alert('Failed to connect to the video session. Please refresh the page and try again.');
        }
    }

    function ensureTwilioAndInit() {
        const primary = 'https://media.twiliocdn.com/sdk/js/video/latest/twilio-video.min.js';
        const fallback = 'https://unpkg.com/twilio-video/dist/twilio-video.min.js';

        function load(src) {
            return new Promise(function (resolve, reject) {
                const s = document.createElement('script');
                s.src = src;
                s.onload = resolve;
                s.onerror = reject;
                document.head.appendChild(s);
            });
        }

        (async function () {
            if (!window.Twilio || !window.Twilio.Video) {
                try {
                    await load(primary);
                } catch (e) {
                    await load(fallback);
                }
            }
            await init();
        })();
    }

    document.addEventListener('DOMContentLoaded', ensureTwilioAndInit);
})();
</script>
@endpush
