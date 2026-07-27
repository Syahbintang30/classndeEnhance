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
    protected $description = 'Migrate all user accounts, transactions, coaching tickets, bookings, and package data from old database SQL dump into current database';

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

        // Temporarily disable foreign key checks for clean bulk import
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            $userCount = $this->migrateUsers($sqlContent);
            $txCount = $this->migrateTransactions($sqlContent);
            $bookingCount = $this->migrateCoachingBookings($sqlContent);
            $ticketCount = $this->migrateCoachingTickets($sqlContent);
            $userPkgCount = $this->migrateUserPackages($sqlContent);
            $voucherCount = $this->migrateVouchers($sqlContent);

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->info("=================================================");
            $this->info(" SUCCESS: All Old Database Data Migrated!        ");
            $this->info(" - Users Synced: {$userCount}");
            $this->info(" - Transactions Synced: {$txCount}");
            $this->info(" - Coaching Bookings Synced: {$bookingCount}");
            $this->info(" - Coaching Tickets Synced: {$ticketCount}");
            $this->info(" - User Packages Synced: {$userPkgCount}");
            $this->info(" - Vouchers Synced: {$voucherCount}");
            $this->info("=================================================");

            return 0;
        } catch (\Throwable $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->error("Database migration failed: " . $e->getMessage());
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

    private function migrateUsers(string $sql): int
    {
        $rows = $this->extractInsertValues($sql, 'users');
        $count = 0;

        foreach ($rows as $rowStr) {
            $cols = $this->parseCsvRow($rowStr);
            if (count($cols) < 10) continue;

            $id = (int) $cols[0];
            $name = $cols[1] ?? 'User';
            $email = strtolower(trim((string)($cols[2] ?? '')));
            $photo = $cols[3] ?? null;
            $phone = $cols[4] ?? null;
            $packageId = is_numeric($cols[5]) ? (int)$cols[5] : null;
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

    private function migrateTransactions(string $sql): int
    {
        $rows = $this->extractInsertValues($sql, 'transactions');
        $count = 0;

        foreach ($rows as $rowStr) {
            $cols = $this->parseCsvRow($rowStr);
            if (count($cols) < 8) continue;

            $id = (int) $cols[0];
            $orderId = $cols[1] ?? '';
            $userId = is_numeric($cols[2]) ? (int)$cols[2] : null;
            $packageId = is_numeric($cols[3]) ? (int)$cols[3] : null;
            $referrerUserId = is_numeric($cols[4] ?? null) ? (int)$cols[4] : null;
            $referralCode = $cols[5] ?? null;
            $method = $cols[6] ?? null;
            $amount = is_numeric($cols[7] ?? 0) ? (float)$cols[7] : 0.0;
            $originalAmount = is_numeric($cols[8] ?? null) ? (int)$cols[8] : null;
            $status = $cols[9] ?? 'settlement';
            $rawMidtrans = $cols[10] ?? null;
            $midtransResponse = null;
            if (! empty($rawMidtrans)) {
                $cleanStr = stripslashes(trim($rawMidtrans, '"\''));
                $decoded = json_decode($cleanStr, true);
                if ($decoded && is_array($decoded)) {
                    $midtransResponse = json_encode($decoded);
                } else {
                    $decodedRaw = json_decode($rawMidtrans, true);
                    if ($decodedRaw && is_array($decodedRaw)) {
                        $midtransResponse = json_encode($decodedRaw);
                    }
                }
            }
            $createdAt = $cols[11] ?? now()->toDateTimeString();
            $updatedAt = $cols[12] ?? now()->toDateTimeString();

            if (empty($orderId)) continue;

            $existing = DB::table('transactions')->where('order_id', $orderId)->first();
            $updateData = [
                'user_id' => $userId,
                'package_id' => $packageId,
                'referrer_user_id' => $referrerUserId,
                'referral_code' => $referralCode,
                'method' => $method,
                'amount' => $amount,
                'original_amount' => $originalAmount,
                'status' => $status,
                'midtrans_response' => $midtransResponse,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];

            if ($existing) {
                DB::table('transactions')->where('order_id', $orderId)->update($updateData);
            } else {
                $updateData['id'] = $id;
                $updateData['order_id'] = $orderId;
                DB::table('transactions')->insert($updateData);
            }

            $count++;
        }

        return $count;
    }

    private function migrateCoachingBookings(string $sql): int
    {
        $rows = $this->extractInsertValues($sql, 'coaching_bookings');
        $count = 0;

        foreach ($rows as $rowStr) {
            $cols = $this->parseCsvRow($rowStr);
            if (count($cols) < 5) continue;

            $id = (int) $cols[0];
            $userId = is_numeric($cols[1]) ? (int)$cols[1] : null;
            $bookingDate = $cols[2] ?? now()->toDateString();
            $timeSlot = $cols[3] ?? '19:00';
            $notes = $cols[4] ?? null;
            $status = $cols[5] ?? 'pending';
            $createdAt = $cols[6] ?? now()->toDateTimeString();
            $updatedAt = $cols[7] ?? now()->toDateTimeString();

            $existing = DB::table('coaching_bookings')->where('id', $id)->first();
            $updateData = [
                'user_id' => $userId,
                'booking_date' => $bookingDate,
                'time_slot' => $timeSlot,
                'notes' => $notes,
                'status' => $status,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];

            if ($existing) {
                DB::table('coaching_bookings')->where('id', $id)->update($updateData);
            } else {
                $updateData['id'] = $id;
                DB::table('coaching_bookings')->insert($updateData);
            }

            $count++;
        }

        return $count;
    }

    private function migrateCoachingTickets(string $sql): int
    {
        $rows = $this->extractInsertValues($sql, 'coaching_tickets');
        $count = 0;

        foreach ($rows as $rowStr) {
            $cols = $this->parseCsvRow($rowStr);
            if (count($cols) < 3) continue;

            $id = (int) $cols[0];
            $userId = is_numeric($cols[1]) ? (int)$cols[1] : null;
            $isUsed = (bool) ($cols[2] ?? 0);
            $source = $cols[3] ?? 'migration';
            $createdAt = $cols[4] ?? now()->toDateTimeString();
            $updatedAt = $cols[5] ?? now()->toDateTimeString();

            $existing = DB::table('coaching_tickets')->where('id', $id)->first();
            $updateData = [
                'user_id' => $userId,
                'is_used' => $isUsed,
                'source' => $source,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];

            if ($existing) {
                DB::table('coaching_tickets')->where('id', $id)->update($updateData);
            } else {
                $updateData['id'] = $id;
                DB::table('coaching_tickets')->insert($updateData);
            }

            $count++;
        }

        return $count;
    }

    private function migrateUserPackages(string $sql): int
    {
        $rows = $this->extractInsertValues($sql, 'user_packages');
        $count = 0;

        foreach ($rows as $rowStr) {
            $cols = $this->parseCsvRow($rowStr);
            if (count($cols) < 3) continue;

            $id = (int) $cols[0];
            $userId = is_numeric($cols[1]) ? (int)$cols[1] : null;
            $packageId = is_numeric($cols[2]) ? (int)$cols[2] : null;
            $createdAt = $cols[3] ?? now()->toDateTimeString();
            $updatedAt = $cols[4] ?? now()->toDateTimeString();

            $existing = DB::table('user_packages')->where('id', $id)->first();
            $updateData = [
                'user_id' => $userId,
                'package_id' => $packageId,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];

            if ($existing) {
                DB::table('user_packages')->where('id', $id)->update($updateData);
            } else {
                $updateData['id'] = $id;
                DB::table('user_packages')->insert($updateData);
            }

            $count++;
        }

        return $count;
    }

    private function migrateVouchers(string $sql): int
    {
        $rows = $this->extractInsertValues($sql, 'vouchers');
        $count = 0;

        foreach ($rows as $rowStr) {
            $cols = $this->parseCsvRow($rowStr);
            if (count($cols) < 3) continue;

            $id = (int) $cols[0];
            $code = $cols[1] ?? '';
            $discountType = $cols[2] ?? 'fixed';
            $discountValue = is_numeric($cols[3] ?? 0) ? (float)$cols[3] : 0.0;
            $createdAt = $cols[4] ?? now()->toDateTimeString();
            $updatedAt = $cols[5] ?? now()->toDateTimeString();

            if (empty($code)) continue;

            $existing = DB::table('vouchers')->where('code', $code)->first();
            $updateData = [
                'discount_type' => $discountType,
                'discount_value' => $discountValue,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];

            if ($existing) {
                DB::table('vouchers')->where('code', $code)->update($updateData);
            } else {
                $updateData['id'] = $id;
                $updateData['code'] = $code;
                DB::table('vouchers')->insert($updateData);
            }

            $count++;
        }

        return $count;
    }
}
