<?php

namespace App\Services;

use App\Models\Package;
use App\Models\User;

class CoachingPricingService
{
    /**
     * Cek apakah user eligible dapat DISKON harga tiket (hanya Intermediate).
     */
    public static function isCoachingMember(?User $user): bool
    {
        if (! $user || empty($user->package_id)) {
            return false;
        }
        $pkg = Package::find($user->package_id);
        if (! $pkg) {
            return false;
        }
        // Hanya intermediate yang dapat diskon member price
        return (string) $pkg->slug === 'intermediate';
    }

    public static function resolveStandaloneTicketUnitPrice(Package $coachingTicketPackage, ?User $user): int
    {
        $fallback = (int) ($coachingTicketPackage->price ?? 0);
        $nonMemberPrice = (int) ($coachingTicketPackage->non_member_price ?? 0);
        if ($nonMemberPrice <= 0) $nonMemberPrice = $fallback;

        $memberPrice = (int) ($coachingTicketPackage->member_price ?? 0);
        if ($memberPrice <= 0) $memberPrice = $fallback;

        if (self::isCoachingMember($user)) {
            return $memberPrice; // 50k untuk intermediate
        }
        return $nonMemberPrice; // 100k untuk semua lainnya
    }
}
