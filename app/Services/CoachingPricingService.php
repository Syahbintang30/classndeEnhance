<?php

namespace App\Services;

use App\Models\Package;
use App\Models\User;

class CoachingPricingService
{
    public static function isCoachingMember(?User $user): bool
    {
        if (! $user || empty($user->package_id)) {
            return false;
        }

        $pkg = Package::find($user->package_id);
        if (! $pkg) {
            return false;
        }

        $eligibleSlugs = config('coaching.eligible_packages', ['beginner', 'intermediate']);
        return in_array((string) $pkg->slug, $eligibleSlugs, true);
    }

    public static function resolveStandaloneTicketUnitPrice(Package $coachingTicketPackage, ?User $user): int
    {
        $fallback = (int) ($coachingTicketPackage->price ?? 0);

        $nonMemberPrice = (int) ($coachingTicketPackage->non_member_price ?? 0);
        if ($nonMemberPrice <= 0) {
            $nonMemberPrice = $fallback;
        }

        $memberPrice = (int) ($coachingTicketPackage->member_price ?? 0);
        if ($memberPrice <= 0) {
            $memberPrice = $fallback;
        }

        if (self::isCoachingMember($user)) {
            return $memberPrice;
        }

        return $nonMemberPrice;
    }
}
