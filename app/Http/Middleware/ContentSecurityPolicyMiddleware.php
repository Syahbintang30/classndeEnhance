<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicyMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Build CSP directives based on application needs
        $cspDirectives = $this->buildCSPDirectives($request);
        
        // Set CSP header
        $response->headers->set('Content-Security-Policy', $cspDirectives);
        
        // Additional security headers
        $this->setSecurityHeaders($response);

        return $response;
    }

    /**
     * Build CSP directives based on application requirements
     */
    private function buildCSPDirectives(Request $request): string
    {
        $directives = [
            'default-src' => ["'self'"],
            'script-src' => [
                "'self'",
                "'unsafe-inline'",
                "'unsafe-eval'",
                'https://cdn.jsdelivr.net',
                'https://unpkg.com',
                'https://cdnjs.cloudflare.com',
                'https://code.jquery.com',
                'https://stackpath.bootstrapcdn.com',
                'https://cdn.tailwindcss.com',
                'https://app.midtrans.com',
                'https://app.sandbox.midtrans.com',
                'https://api.instagram.com',
                'https://syndication.twitter.com',
                'https://platform.twitter.com',
            ],
            // Explicit script-src-elem avoids fallback ambiguity in browser console.
            'script-src-elem' => [
                "'self'",
                "'unsafe-inline'",
                "'unsafe-eval'",
                'https://cdn.jsdelivr.net',
                'https://unpkg.com',
                'https://cdnjs.cloudflare.com',
                'https://code.jquery.com',
                'https://stackpath.bootstrapcdn.com',
                'https://cdn.tailwindcss.com',
                'https://app.midtrans.com',
                'https://app.sandbox.midtrans.com',
                'https://api.instagram.com',
                'https://syndication.twitter.com',
                'https://platform.twitter.com',
            ],
            'style-src' => [
                "'self'",
                "'unsafe-inline'",
                'https://fonts.googleapis.com',
                'https://cdn.jsdelivr.net',
                'https://unpkg.com',
                'https://cdnjs.cloudflare.com',
                'https://stackpath.bootstrapcdn.com',
            ],
            'font-src' => [
                "'self'",
                'https://fonts.gstatic.com',
                'https://fonts.googleapis.com',
                'https://cdnjs.cloudflare.com',
                'https://use.fontawesome.com',
                'https://cdn.jsdelivr.net',
                'data:',
            ],
            'img-src' => ["'self'", 'data:', 'blob:', 'https:', 'http:'],
            'media-src' => ["'self'", 'blob:', 'https://video.bunnycdn.com', 'https://*.bunnycdn.com', 'https://*.b-cdn.net'],
            'connect-src' => [
                "'self'",
                'https://api.midtrans.com',
                'https://app.midtrans.com',
                'https://api.sandbox.midtrans.com',
                'https://app.sandbox.midtrans.com',
                'https://video.bunnycdn.com',
                'https://*.bunnycdn.com',
                'https://*.b-cdn.net',
                'https://api.instagram.com',
                'https://syndication.twitter.com',
                'https://platform.twitter.com',
                'wss:',
                'ws:',
            ],
            'frame-src' => ["'self'", 'https://app.midtrans.com', 'https://api.midtrans.com', 'https://app.sandbox.midtrans.com', 'https://api.sandbox.midtrans.com'],
            'object-src' => ["'none'"],
            'base-uri' => ["'self'"],
            'form-action' => ["'self'"],
            'frame-ancestors' => ["'self'"],
        ];

        // Relax CSP specifically for the coaching session page to allow Twilio SDK and signaling.
        if ($request->is('coaching/session/*')) {
            $directives['script-src'] = array_merge($directives['script-src'], ['https://media.twiliocdn.com']);
            $directives['script-src-elem'] = array_merge($directives['script-src-elem'], ['https://media.twiliocdn.com']);
            $directives['connect-src'] = array_merge($directives['connect-src'], [
                'https://api.twilio.com',
                'https://video.twilio.com',
                'https://media.twiliocdn.com',
                'https://*.twilio.com',
            ]);
        }

        $parts = [];
        foreach ($directives as $name => $sources) {
            $uniq = array_values(array_unique(array_filter($sources)));
            $parts[] = $name . ' ' . implode(' ', $uniq);
        }
        $parts[] = 'upgrade-insecure-requests';

        return implode('; ', $parts);
    }

    /**
     * Set additional security headers
     */
    private function setSecurityHeaders(Response $response): void
    {
        // X-Frame-Options for clickjacking protection
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        
        // X-Content-Type-Options to prevent MIME sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // X-XSS-Protection for legacy browsers
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Permissions Policy (formerly Feature Policy)
        // Default deny camera/microphone; allow on specific coaching routes via route-scoped headers below.
        $policy = 'camera=(), microphone=(), geolocation=(), fullscreen=(self), payment=(self)';
        try {
            $req = request();
            if ($req && $req->is('coaching/session/*')) {
                // Allow camera/mic for live coaching session page
                $policy = 'camera=(self), microphone=(self), geolocation=(), fullscreen=(self), payment=(self)';
            }
        } catch (\Throwable $e) { /* ignore */ }
        $response->headers->set('Permissions-Policy', $policy);
        
        // Strict Transport Security (HTTPS only in production)
        if (app()->environment('production') && request()->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }
        
        // Remove server information
        $response->headers->remove('Server');
        $response->headers->remove('X-Powered-By');
    }
}