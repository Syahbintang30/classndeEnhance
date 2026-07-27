<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Package;
use App\Services\CoachingTicketService;

class GrantPackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'member:grant {email : The email of the user} {--package=intermediate : Package slug (beginner|intermediate)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grant course package membership and coaching tickets to a user by email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = trim($this->argument('email'));
        $pkgSlug = trim((string) $this->option('package'));

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User with email '{$email}' not found.");
            return 1;
        }

        $package = Package::where('slug', $pkgSlug)->first();
        if (! $package) {
            $package = Package::where('slug', 'intermediate')->first();
        }

        if (! $package) {
            $this->error("Package '{$pkgSlug}' not found.");
            return 1;
        }

        // Grant package membership
        $user->package_id = $package->id;
        if (! $user->email_verified_at) {
            $user->email_verified_at = now();
        }
        $user->save();

        // Ensure coaching tickets are granted according to package (2 tickets for Intermediate, 1 for Beginner)
        if (class_exists(CoachingTicketService::class)) {
            CoachingTicketService::ensureFreeTickets($user);
        }

        $activeTickets = \App\Models\CoachingTicket::where('user_id', $user->id)->where('is_used', false)->count();

        $this->info("Successfully granted {$package->name} Package membership to {$user->name} ({$user->email}). Active Coaching Tickets: {$activeTickets}");
        return 0;
    }
}
