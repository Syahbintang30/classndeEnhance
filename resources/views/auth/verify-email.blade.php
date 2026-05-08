<x-guest-layout>
    @php
        $supportsEmailVerificationSend = config('mail.default') !== 'log';
    @endphp

    <div class="verify-shell">
        <div class="verify-icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                <path d="M3 7l9 6 9-6"></path>
            </svg>
        </div>

        <h1 class="verify-title">Verify your email first</h1>
        <p class="verify-text">
            To activate your account, open the verification link we sent to your email.
            @if($supportsEmailVerificationSend)
                If you have not received it yet, click resend and check your inbox or spam folder.
            @else
                Local mode is not sending real emails right now because the mailer is set to log.
            @endif
        </p>

        @if (session('status'))
            <div class="verify-alert success">
                {{ session('status') == 'verification-link-sent' ? 'A new verification link has been sent. Please check your inbox or spam folder.' : session('status') }}
            </div>
        @endif

        <div class="verify-actions">
            @if($supportsEmailVerificationSend)
                <form method="POST" action="{{ route('verification.send') }}" class="verify-form">
                    @csrf
                    <button type="submit" class="btn-main">
                        Resend verification email
                    </button>
                </form>
            @endif

            <a href="{{ route('auth.google.redirect') }}" class="btn-google">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48" aria-hidden="true" focusable="false">
                    <path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.7 32.7 29.3 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3 0 5.7 1.1 7.8 2.9l5.7-5.7C34 6.1 29.3 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.4-.4-3.5z"/>
                    <path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.7 15 18.9 12 24 12c3 0 5.7 1.1 7.8 2.9l5.7-5.7C34 6.1 29.3 4 24 4c-7.7 0-14.4 4.4-17.7 10.7z"/>
                    <path fill="#4CAF50" d="M24 44c5.1 0 9.8-2 13.3-5.2l-6.1-5.2C29.2 35.1 26.7 36 24 36c-5.3 0-9.7-3.3-11.3-8l-6.6 5.1C9.3 39.5 16.1 44 24 44z"/>
                    <path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.5-2.4 4.6-4.4 6.1l.1-.1 6.1 5.2C36.7 39.5 44 34 44 24c0-1.3-.1-2.4-.4-3.5z"/>
                </svg>
                Connect with Google (optional)
            </a>

            <form method="POST" action="{{ route('logout') }}" class="verify-form">
                @csrf
                <button type="submit" class="btn-ghost">
                    Log out
                </button>
            </form>
        </div>
    </div>

    <style>
        body {
            background: radial-gradient(circle at 20% 20%, #111827 0%, #05070d 55%, #03050b 100%) !important;
        }

        .min-h-screen {
            background: transparent !important;
            padding: 44px 18px !important;
        }

        .min-h-screen > div:last-child {
            width: min(560px, 100%);
            margin-top: 16px !important;
            padding: 28px !important;
            border-radius: 18px !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(250, 250, 251, 0.97)) !important;
            box-shadow: 0 24px 50px rgba(2, 6, 23, 0.48) !important;
        }

        .verify-shell {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .verify-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #e5e7eb;
            background: linear-gradient(135deg, #111827, #1f2937);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 10px 28px rgba(2, 6, 23, 0.28);
        }

        .verify-title {
            margin: 0;
            font-size: 1.5rem;
            line-height: 1.2;
            font-weight: 700;
            color: #111827;
        }

        .verify-text {
            margin: 0;
            font-size: 0.95rem;
            line-height: 1.65;
            color: #4b5563;
        }

        .verify-alert {
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 0.9rem;
            line-height: 1.4;
            border: 1px solid transparent;
        }

        .verify-alert.success {
            color: #1f2937;
            background: #f3f4f6;
            border-color: #d1d5db;
        }

        .verify-actions {
            margin-top: 4px;
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .verify-form {
            margin: 0;
        }

        .btn-main,
        .btn-ghost,
        .btn-google {
            width: 100%;
            min-height: 44px;
            border-radius: 10px;
            font-size: 0.92rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-google {
            color: #111827;
            background: #ffffff;
            border-color: #e5e7eb;
            box-shadow: 0 8px 20px rgba(2, 6, 23, 0.12);
        }

        .btn-google:hover {
            background: #f8fafc;
            border-color: #d1d5db;
        }

        .btn-main {
            color: #ffffff;
            background: linear-gradient(135deg, #0f172a, #1f2937);
            border-color: #111827;
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.32);
        }

        .btn-main:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 26px rgba(15, 23, 42, 0.42);
        }

        .btn-ghost {
            color: #374151;
            background: #ffffff;
            border-color: #e5e7eb;
        }

        .btn-ghost:hover {
            color: #111827;
            border-color: #cbd5e1;
            background: #f9fafb;
        }
    </style>

    <script>
        // If verification happens in another tab/device, auto-refresh this page into the proper destination.
        (function() {
            const noticeUrl = '{{ route('verification.notice') }}';
            let attempts = 0;
            const maxAttempts = 60; // ~2 minutes at 2s interval

            async function checkVerificationState() {
                attempts += 1;
                try {
                    const response = await fetch(noticeUrl, {
                        method: 'GET',
                        credentials: 'same-origin',
                        redirect: 'follow',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html,application/xhtml+xml'
                        }
                    });

                    if (response.redirected && response.url && !response.url.includes('/verify-email')) {
                        window.location.href = response.url;
                        return;
                    }
                } catch (e) {
                    // ignore transient polling errors
                }

                if (attempts < maxAttempts) {
                    setTimeout(checkVerificationState, 2000);
                }
            }

            setTimeout(checkVerificationState, 2000);
        })();
    </script>
</x-guest-layout>
