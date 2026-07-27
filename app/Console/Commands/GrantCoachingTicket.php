<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\CoachingTicket;

class GrantCoachingTicket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coaching:grant {email : The email of the user} {--count=1 : Number of tickets to grant}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grant 1 or more Coaching Tickets to a user by email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = trim($this->argument('email'));
        $count = max(1, (int) $this->option('count'));

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User with email '{$email}' not found.");
            return 1;
        }

        $created = 0;
        for ($i = 0; $i < $count; $i++) {
            CoachingTicket::create([
                'user_id' => $user->id,
                'is_used' => false,
                'source' => 'admin_command',
            ]);
            $created++;
        }

        $totalActive = CoachingTicket::where('user_id', $user->id)->where('is_used', false)->count();

        $this->info("Successfully granted {$created} coaching ticket(s) to {$user->name} ({$user->email}). Total active tickets: {$totalActive}");
        return 0;
    }
}
