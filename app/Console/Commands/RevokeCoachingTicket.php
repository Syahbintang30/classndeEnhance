<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\CoachingTicket;

class RevokeCoachingTicket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coaching:set-tickets {email : The email of the user} {--target=1 : Target number of active tickets}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set exact number of active coaching tickets for a user by email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = trim($this->argument('email'));
        $target = max(0, (int) $this->option('target'));

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User with email '{$email}' not found.");
            return 1;
        }

        $activeTickets = CoachingTicket::where('user_id', $user->id)
            ->where('is_used', false)
            ->orderByDesc('id')
            ->get();

        $currentCount = $activeTickets->count();

        if ($currentCount > $target) {
            $toRemove = $currentCount - $target;
            foreach ($activeTickets->take($toRemove) as $t) {
                $t->delete();
            }
            $this->info("Removed {$toRemove} extra ticket(s). User {$user->email} now has exactly {$target} active ticket(s).");
        } elseif ($currentCount < $target) {
            $toAdd = $target - $currentCount;
            for ($i = 0; $i < $toAdd; $i++) {
                CoachingTicket::create([
                    'user_id' => $user->id,
                    'is_used' => false,
                    'source' => 'admin_command',
                ]);
            }
            $this->info("Added {$toAdd} ticket(s). User {$user->email} now has exactly {$target} active ticket(s).");
        } else {
            $this->info("User {$user->email} already has exactly {$target} active ticket(s). No changes made.");
        }

        return 0;
    }
}
