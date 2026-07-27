<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportLegacyDatabase extends Command
{
    protected $signature = 'db:import-legacy';
    protected $description = 'Import user accounts and user-related data from legacy SQL dump into active database';

    public function handle()
    {
        $sqlFile = base_path('weblama/u650263172_classnde.sql');

        if (! File::exists($sqlFile)) {
            $this->error("SQL file not found at: {$sqlFile}");
            return Command::FAILURE;
        }

        $this->info("Step 1: Running migrations to ensure all current tables exist...");
        Artisan::call('migrate', ['--force' => true]);

        $this->info("Step 2: Importing user accounts and user data...");

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $sqlContent = File::get($sqlFile);

            // Extract ONLY INSERT statements for user-related tables
            $targetTables = ['users', 'user_packages', 'coaching_tickets', 'coaching_bookings', 'transactions'];

            foreach ($targetTables as $table) {
                DB::table($table)->truncate();
                
                // Match INSERT INTO `tablename` ... ;
                $pattern = '/INSERT INTO `' . preg_quote($table, '/') . '` [^;]+;/s';
                if (preg_match($pattern, $sqlContent, $matches)) {
                    DB::unprepared($matches[0]);
                }
            }

            // Ensure admin accounts exist and have default accessible passwords
            DB::table('users')->where('email', 'super@admin')->update([
                'password' => \Illuminate\Support\Facades\Hash::make('superadminpass'),
                'is_admin' => 1,
                'is_superadmin' => 1,
            ]);
            DB::table('users')->where('email', 'admin@admin')->update([
                'password' => \Illuminate\Support\Facades\Hash::make('adminpass'),
                'is_admin' => 1,
                'is_superadmin' => 0,
            ]);

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Refresh package data with clean seeder
            $this->info("Refreshing package details & pricing...");
            Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\PackageSeeder', '--force' => true]);

            $userCount = DB::table('users')->count();
            $this->info("Successfully imported user accounts! Total Users in DB: {$userCount}");

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("Failed to import user database: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

