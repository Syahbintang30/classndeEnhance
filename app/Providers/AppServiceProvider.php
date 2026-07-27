<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use App\Models\CoachingTicket;
use App\Observers\UserObserver;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ensure framework storage directories exist automatically
        $requiredDirs = [
            storage_path('framework/views'),
            storage_path('framework/cache/data'),
            storage_path('framework/sessions'),
            storage_path('logs'),
        ];
        foreach ($requiredDirs as $dir) {
            if (! is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
        }

        // Ensure PHP's default timezone matches the app configuration
        date_default_timezone_set(config('app.timezone'));
        
        // Register user observer to ensure programmatic user creation also receives a free ticket
        User::observe(UserObserver::class);

        // Define admin gate: users with is_admin = true OR is_superadmin = true
        Gate::define('admin', function (?User $user) {
            return $user && ($user->is_admin || $user->is_superadmin);
        });

        // Custom High-End Dark Glassmorphic Email Verification Template
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Verify Email Address - Guitarclassbynde')
                ->view('emails.verify-email', ['user' => $notifiable, 'url' => $url]);
        });

        // Custom High-End Dark Glassmorphic Reset Password Template
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('Reset Password - Guitarclassbynde')
                ->view('emails.reset-password', ['user' => $notifiable, 'url' => $url]);
        });
    }
}
