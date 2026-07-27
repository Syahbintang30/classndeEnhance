<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MigrateOldDbCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-old-db {file=weblama/u650263172_classnde.sql}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Safely migrate existing user account credentials from old database SQL dump without touching course content or new system tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = base_path($this->argument('file'));

        if (! File::exists($filePath)) {
            $this->error("SQL dump file not found at: {$filePath}");
            return 1;
        }

        $this->info("Reading SQL dump file: {$filePath}...");
        $sqlContent = File::get($filePath);

        DB::beginTransaction();

        try {
            // STRICTLY MIGRATE USER ACCOUNTS ONLY
            $userCount = $this->migrateUsersOnly($sqlContent);

            DB::commit();

            $this->info("=================================================");
            $this->info(" SUCCESS: User Accounts Migrated Safely!         ");
            $this->info(" - Total Existing Users Migrated/Synced: {$userCount} ");
            $this->info(" - Course lessons, topics & new features: UNTOUCHED ");
            $this->info("=================================================");

            return 0;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error("User migration failed: " . $e->getMessage());
            return 1;
        }
    }

    private function extractInsertValues(string $sql, string $tableName): array
    {
        $pattern = '/INSERT INTO `' . preg_quote($tableName, '/') . '` \([^\)]+\) VALUES\s*(.*?);/s';
        if (! preg_match($pattern, $sql, $matches)) {
            return [];
        }

        $valuesBlock = trim($matches[1]);
        if (empty($valuesBlock)) {
            return [];
        }

        $rows = [];
        $length = strlen($valuesBlock);
        $inString = false;
        $escaped = false;
        $current = '';

        for ($i = 0; $i < $length; $i++) {
            $char = $valuesBlock[$i];

            if ($char === "'" && ! $escaped) {
                $inString = ! $inString;
            }

            if ($char === '\\' && $inString) {
                $escaped = ! $escaped;
            } else {
                $escaped = false;
            }

            if (! $inString && $char === '(') {
                $current = '';
                continue;
            }

            if (! $inString && $char === ')') {
                $rows[] = $current;
                $current = '';
                continue;
            }

            $current .= $char;
        }

        return array_filter(array_map('trim', $rows));
    }

    private function parseCsvRow(string $rowString): array
    {
        $values = [];
        $length = strlen($rowString);
        $inString = false;
        $escaped = false;
        $current = '';

        for ($i = 0; $i < $length; $i++) {
            $char = $rowString[$i];

            if ($char === "'" && ! $escaped) {
                $inString = ! $inString;
                continue;
            }

            if ($char === '\\' && $inString) {
                $next = $rowString[$i + 1] ?? '';
                if ($next === "'" || $next === '\\') {
                    $current .= $next;
                    $i++;
                    continue;
                }
            }

            if (! $inString && $char === ',') {
                $values[] = trim($current);
                $current = '';
                continue;
            }

            $current .= $char;
        }

        $values[] = trim($current);

        return array_map(function ($val) {
            $val = trim($val);
            if (strtoupper($val) === 'NULL') return null;
            return $val;
        }, $values);
    }

    private function migrateUsersOnly(string $sql): int
    {
        $rows = $this->extractInsertValues($sql, 'users');
        $validPackageIds = DB::table('packages')->pluck('id')->toArray();
        $count = 0;

        foreach ($rows as $rowStr) {
            $cols = $this->parseCsvRow($rowStr);
            if (count($cols) < 10) continue;

            $id = (int) $cols[0];
            $name = $cols[1] ?? 'User';
            $email = strtolower(trim((string)($cols[2] ?? '')));
            $photo = $cols[3] ?? null;
            $phone = $cols[4] ?? null;
            
            $rawPackageId = is_numeric($cols[5]) ? (int)$cols[5] : null;
            $packageId = ($rawPackageId && in_array($rawPackageId, $validPackageIds)) ? $rawPackageId : null;

            $emailVerifiedAt = $cols[6] ?? null;
            $password = $cols[7] ?? '';
            $isAdmin = (bool) ($cols[8] ?? 0);
            $isSuperAdmin = (bool) ($cols[9] ?? 0);
            $rememberToken = $cols[10] ?? null;
            $referralCode = $cols[11] ?? null;
            $referredBy = is_numeric($cols[12] ?? null) ? (int)$cols[12] : null;
            $createdAt = $cols[13] ?? now()->toDateTimeString();
            $updatedAt = $cols[14] ?? now()->toDateTimeString();

            if (empty($email)) continue;

            // Auto-grant LMS access for users who bought packages or are admins
            $hasLmsAccess = ($isAdmin || $isSuperAdmin || ! empty($packageId));

            $existing = DB::table('users')->where('email', $email)->first();

            $updateData = [
                'name' => $name,
                'photo' => $photo,
                'phone' => $phone,
                'package_id' => $packageId,
                'email_verified_at' => $emailVerifiedAt,
                'password' => $password,
                'is_admin' => $isAdmin,
                'is_superadmin' => $isSuperAdmin,
                'remember_token' => $rememberToken,
                'referral_code' => $referralCode,
                'referred_by' => $referredBy,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];

            if ($existing) {
                DB::table('users')->where('email', $email)->update($updateData);
            } else {
                $updateData['id'] = $id;
                $updateData['email'] = $email;
                DB::table('users')->insert($updateData);
            }

            $count++;
        }

        return $count;
    }
}
