<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportLegacyDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:import-legacy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all legacy users, packages, lessons, topics, coaching tickets, and settings into the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sqlFile = base_path('weblama/u650263172_classnde.sql');

        if (! File::exists($sqlFile)) {
            $this->error("SQL file not found at: {$sqlFile}");
            return Command::FAILURE;
        }

        $this->info("Importing database from {$sqlFile}...");

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $tables = DB::select('SHOW TABLES');
            foreach ($tables as $t) {
                $arr = array_values((array)$t);
                if (!empty($arr[0])) {
                    DB::statement('DROP TABLE IF EXISTS `' . $arr[0] . '`');
                }
            }

            $sqlContent = File::get($sqlFile);
            DB::unprepared($sqlContent);

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $userCount = DB::table('users')->count();
            $this->info("Successfully imported database! Total Users in DB: {$userCount}");

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("Failed to import database: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
