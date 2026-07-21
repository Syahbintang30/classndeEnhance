<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class PruneUnverifiedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:prune-unverified {--hours=24 : Age in hours threshold for unverified users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unverified user accounts older than a specified number of hours';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $hours = (int) $this->option('hours');
        if ($hours <= 0) {
            $hours = 24;
        }

        $cutoff = Carbon::now()->subHours($hours);

        $this->info("Scanning for unverified users created before {$cutoff->toDateTimeString()} ({$hours} hours ago)...");

        // Query users with email_verified_at = null, not admin/superadmin, created before threshold
        $query = User::query()
            ->whereNull('email_verified_at')
            ->where('is_admin', false)
            ->where('is_superadmin', false)
            ->where('created_at', '<=', $cutoff);

        // Safety check: do not delete users who have successful transactions
        $successStatuses = ['settlement', 'capture', 'success', 'paid', 'settled', 'completed'];
        $usersWithTxns = Transaction::whereIn('status', $successStatuses)->pluck('user_id')->filter()->unique();

        if ($usersWithTxns->isNotEmpty()) {
            $query->whereNotIn('id', $usersWithTxns);
        }

        $usersToDelete = $query->get();
        $count = $usersToDelete->count();

        if ($count === 0) {
            $this->info("No unverified stale users found to prune.");
            return 0;
        }

        $deletedCount = 0;
        foreach ($usersToDelete as $user) {
            try {
                $this->line("Pruning unverified user ID #{$user->id}: {$user->name} ({$user->email}) - created {$user->created_at->diffForHumans()}");
                $user->delete();
                $deletedCount++;
            } catch (\Throwable $e) {
                $this->error("Failed to delete user ID #{$user->id}: " . $e->getMessage());
            }
        }

        $this->info("Successfully pruned {$deletedCount} unverified user account(s).");
        return 0;
    }
}
