@extends('layouts.app')

@section('title', 'Song Tutorial')

@section('content')
<div class="lms-dashboard-shell" style="min-height: calc(100vh - 120px); padding: 48px 20px; text-align: center;">
    <div style="max-width: 520px; margin: 0 auto;">
        <h1 style="margin: 0 0 12px; font-size: 28px; font-weight: 800; color: var(--lms-text);">Song Tutorial</h1>
        @auth
            @if (! empty($hasIntermediate))
                <p style="color: var(--lms-muted); line-height: 1.6;">No Song Tutorial content is available yet. Please check back later.</p>
            @else
                <p style="color: var(--lms-muted); line-height: 1.6; margin-bottom: 24px;">
                    Song Tutorial access is available for the <strong>Intermediate</strong> package, not the Beginner package. Upgrade your package to unlock the song library and technique breakdowns.
                </p>
                <a href="{{ route('registerclass') }}" style="display: inline-block; padding: 12px 24px; border-radius: 12px; background: var(--lms-btn-bg); color: var(--lms-btn-text); font-weight: 700; text-decoration: none;">
                    View packages &amp; upgrade
                </a>
            @endif
        @else
            <p style="color: var(--lms-muted); line-height: 1.6; margin-bottom: 24px;">
                Sign in with an account that has the Intermediate package to access Song Tutorial.
            </p>
            <a href="{{ route('login') }}" style="display: inline-block; padding: 12px 24px; border-radius: 12px; background: var(--lms-btn-bg); color: var(--lms-btn-text); font-weight: 700; text-decoration: none;">
                Login
            </a>
        @endauth
    </div>
</div>
@endsection
