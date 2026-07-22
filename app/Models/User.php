<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'package_id',
        'referral_code',
        'referred_by',
        'is_admin',
        'is_superadmin',
        'photo',
        'google_id',
        'email_verified_at',
        'xp',
    ];

    public function getGuitarRankAttribute(): array
    {
        $xp = (int) ($this->xp ?? 0);
        if ($xp >= 4501) {
            return [
                'name' => 'Mythic Shred Legend',
                'tier' => 'Mythic',
                'icon' => 'fa-crown',
                'color' => '#EC4899',
                'badge_bg' => 'bg-pink-500/10 border-pink-500/30 text-pink-400',
                'next_xp' => 10000,
                'min_xp' => 4501,
            ];
        } elseif ($xp >= 2501) {
            return [
                'name' => 'Diamond Virtuoso',
                'tier' => 'Diamond',
                'icon' => 'fa-wand-magic-sparkles',
                'color' => '#A855F7',
                'badge_bg' => 'bg-purple-500/10 border-purple-500/30 text-purple-400',
                'next_xp' => 4500,
                'min_xp' => 2501,
            ];
        } elseif ($xp >= 1201) {
            return [
                'name' => 'Platinum Riff Lord',
                'tier' => 'Platinum',
                'icon' => 'fa-gem',
                'color' => '#38BDF8',
                'badge_bg' => 'bg-sky-500/10 border-sky-500/30 text-sky-400',
                'next_xp' => 2500,
                'min_xp' => 1201,
            ];
        } elseif ($xp >= 601) {
            return [
                'name' => 'Gold Licksmith',
                'tier' => 'Gold',
                'icon' => 'fa-trophy',
                'color' => '#F59E0B',
                'badge_bg' => 'bg-amber-500/10 border-amber-500/30 text-amber-400',
                'next_xp' => 1200,
                'min_xp' => 601,
            ];
        } elseif ($xp >= 251) {
            return [
                'name' => 'Silver Fretmaster',
                'tier' => 'Silver',
                'icon' => 'fa-medal',
                'color' => '#94A3B8',
                'badge_bg' => 'bg-slate-400/10 border-slate-400/30 text-slate-300',
                'next_xp' => 600,
                'min_xp' => 251,
            ];
        } else {
            return [
                'name' => 'Bronze Shredder',
                'tier' => 'Bronze',
                'icon' => 'fa-award',
                'color' => '#D97706',
                'badge_bg' => 'bg-amber-700/10 border-amber-700/30 text-amber-600',
                'next_xp' => 250,
                'min_xp' => 0,
            ];
        }
    }

    /**
     * Return public URL for user's photo or null.
     */
    public function photoUrl(): ?string
    {
        if (! $this->photo) return null;
        // If photo already contains http(s) assume it's an external URL
        if (preg_match('#^https?://#i', $this->photo)) return $this->photo;
        return asset('storage/' . ltrim($this->photo, '/'));
    }

    public function package()
    {
        return $this->belongsTo(\App\Models\Package::class);
    }

    public function referredBy()
    {
        return $this->belongsTo(self::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(self::class, 'referred_by');
    }

    public function coachingTickets()
    {
        return $this->hasMany(\App\Models\CoachingTicket::class);
    }

    public function topicProgresses()
    {
        return $this->hasMany(\App\Models\TopicProgress::class);
    }

    /**
     * Check whether user should be allowed to enter LMS course pages.
     */
    public function hasLmsAccess(): bool
    {
        $coachingSlug = config('coaching.coaching_package_slug', 'coaching-ticket');

        if (! empty($this->package_id)) {
            try {
                $pkg = \App\Models\Package::find($this->package_id);
                if ($pkg && ($pkg->slug ?? null) !== $coachingSlug) {
                    return true;
                }
            } catch (\Throwable $e) {
                // ignore and continue fallback checks
            }
        }

        try {
            $hasHistoricalPackage = \App\Models\UserPackage::where('user_id', $this->id)
                ->whereHas('package', function ($q) use ($coachingSlug) {
                    $q->where('slug', '!=', $coachingSlug);
                })
                ->exists();
            if ($hasHistoricalPackage) {
                return true;
            }
        } catch (\Throwable $e) {
            // ignore and continue fallback checks
        }

        try {
            // Fallback for users whose entitlement was not persisted yet,
            // but payment transaction is already settled/captured.
            $successStatuses = ['settlement', 'capture', 'success', 'paid', 'settled', 'completed', 'approve'];
            $successfulTxns = \App\Models\Transaction::where('user_id', $this->id)
                ->whereIn('status', $successStatuses)
                ->latest('id')
                ->limit(5)
                ->get();

            if ($successfulTxns->isNotEmpty()) {
                foreach ($successfulTxns as $txn) {
                    $candidatePackageId = $txn->package_id;

                    if (empty($candidatePackageId) && ! empty($txn->order_id)) {
                        try {
                            $cached = \Illuminate\Support\Facades\Cache::get('pending_txn:' . $txn->order_id);
                            if (is_array($cached) && ! empty($cached['package_id'])) {
                                $candidatePackageId = (int) $cached['package_id'];
                            }
                        } catch (\Throwable $e) {
                            // ignore cache failures
                        }
                    }

                    if (empty($candidatePackageId)) {
                        $payload = $txn->midtrans_response;
                        if (is_string($payload)) {
                            $payload = json_decode($payload, true) ?: [];
                        }

                        if (is_array($payload)) {
                            if (! empty($payload['package_id'])) {
                                $candidatePackageId = (int) $payload['package_id'];
                            } elseif (! empty($payload['item_details'][0]['id'])) {
                                $itemId = (string) $payload['item_details'][0]['id'];
                                if (preg_match('/^package-(\d+)$/', $itemId, $matches)) {
                                    $candidatePackageId = (int) ($matches[1] ?? 0);
                                }
                            }
                        }
                    }

                    if (! empty($candidatePackageId)) {
                        $package = \App\Models\Package::find($candidatePackageId);
                        if ($package && ($package->slug ?? null) === $coachingSlug) {
                            continue;
                        }

                        // Persist repaired linkage for future checks.
                        try {
                            if (! empty($txn->id) && empty($txn->package_id)) {
                                \App\Models\Transaction::where('id', $txn->id)->update(['package_id' => $candidatePackageId]);
                            }
                        } catch (\Throwable $e) {
                            // ignore repair failures
                        }

                        try {
                            \App\Models\UserPackage::firstOrCreate(
                                ['user_id' => $this->id, 'package_id' => $candidatePackageId],
                                ['purchased_at' => now(), 'source' => 'midtrans-recovery']
                            );
                        } catch (\Throwable $e) {
                            // ignore recovery failures
                        }

                        if (empty($this->package_id)) {
                            try {
                                $this->package_id = $candidatePackageId;
                                $this->save();
                            } catch (\Throwable $e) {
                                // ignore update failures
                            }
                        }

                        return true;
                    }
                }

                return false;
            }
        } catch (\Throwable $e) {
            // ignore and return false below
        }

        return false;
    }

    /**
     * Check whether user has coaching-only entitlement.
     * This is intentionally separate from LMS course entitlement.
     */
    public function hasCoachingAccess(): bool
    {
        $coachingSlug = config('coaching.coaching_package_slug', 'coaching-ticket');

        try {
            if (! empty($this->package_id)) {
                $pkg = \App\Models\Package::find($this->package_id);
                if ($pkg && ($pkg->slug ?? null) === $coachingSlug) {
                    return true;
                }
            }
        } catch (\Throwable $e) {
            // ignore and continue
        }

        try {
            if (\App\Models\CoachingTicket::where('user_id', $this->id)->exists()) {
                return true;
            }
        } catch (\Throwable $e) {
            // ignore and continue
        }

        try {
            if (\App\Models\CoachingBooking::where('user_id', $this->id)->exists()) {
                return true;
            }
        } catch (\Throwable $e) {
            // ignore and continue
        }

        // Recovery path for legacy transactions where coaching ticket rows may be missing
        // but payment has already settled successfully.
        try {
            $successfulTxns = \App\Models\Transaction::query()
                ->where('user_id', $this->id)
                ->whereIn('status', ['settlement', 'capture', 'success', 'paid', 'settled'])
                ->latest()
                ->get();

            foreach ($successfulTxns as $txn) {
                $candidatePackageId = (int) ($txn->package_id ?? 0);

                if (empty($candidatePackageId)) {
                    $payload = $txn->midtrans_response;
                    if (is_string($payload)) {
                        $payload = json_decode($payload, true) ?: [];
                    }

                    if (is_array($payload)) {
                        if (! empty($payload['package_id'])) {
                            $candidatePackageId = (int) $payload['package_id'];
                        } elseif (! empty($payload['item_details'][0]['id'])) {
                            $itemId = (string) $payload['item_details'][0]['id'];
                            if (preg_match('/^package-(\d+)$/', $itemId, $matches)) {
                                $candidatePackageId = (int) ($matches[1] ?? 0);
                            }
                        }
                    }
                }

                if (! empty($candidatePackageId)) {
                    $pkg = \App\Models\Package::find($candidatePackageId);
                    if ($pkg && ($pkg->slug ?? null) === $coachingSlug) {
                        return true;
                    }
                }
            }
        } catch (\Throwable $e) {
            // ignore and continue
        }

        return false;
    }

    /**
     * Check if user is an Intermediate package member (or admin).
     */
    public function isIntermediateMember(): bool
    {
        if (($this->is_admin ?? false) || ($this->is_superadmin ?? false) || $this->hasIntermediateAccess()) {
            return true;
        }

        $intermediateSlug = 'intermediate';
        if (! empty($this->package_id)) {
            $pkg = \App\Models\Package::find($this->package_id);
            if ($pkg && ($pkg->slug ?? null) === $intermediateSlug) {
                return true;
            }
        }

        return \App\Models\UserPackage::where('user_id', $this->id)
            ->whereHas('package', function ($q) use ($intermediateSlug) {
                $q->where('slug', $intermediateSlug);
            })
            ->exists();
    }

    /**
     * Check if user is a Beginner / Reguler package member (and not Intermediate).
     */
    public function isBeginnerMember(): bool
    {
        if ($this->isIntermediateMember()) {
            return false;
        }

        $beginnerSlug = 'beginner';
        if (! empty($this->package_id)) {
            $pkg = \App\Models\Package::find($this->package_id);
            if ($pkg && ($pkg->slug ?? null) === $beginnerSlug) {
                return true;
            }
        }

        return \App\Models\UserPackage::where('user_id', $this->id)
            ->whereHas('package', function ($q) use ($beginnerSlug) {
                $q->where('slug', $beginnerSlug);
            })
            ->exists();
    }





    /**
     * Check if user has intermediate package access
     * Uses configurable package ID and slugs instead of hardcoded values
     */
    public function hasIntermediateAccess()
    {
        if (!$this->package_id) {
            return false;
        }

        // Check by numeric ID (configurable via settings)
        $intermediatePackageId = \App\Models\Setting::getIntermediatePackageId();
        if ($this->package_id == $intermediatePackageId) {
            return true;
        }

        // Check by package slug (configurable via settings)
        try {
            $package = \App\Models\Package::find($this->package_id);
            if ($package && $package->slug) {
                $allowedSlugs = \App\Models\Setting::getIntermediatePackageSlugs();
                if (in_array($package->slug, $allowedSlugs)) {
                    return true;
                }
            }
        } catch (\Throwable $e) {
            // Ignore package lookup failures
        }

        // Check historical purchases via user_packages
        try {
            $allowedSlugs = \App\Models\Setting::getIntermediatePackageSlugs();
            $exists = \App\Models\UserPackage::where('user_id', $this->id)
                ->whereHas('package', function($q) use ($allowedSlugs) {
                    $q->whereIn('slug', $allowedSlugs);
                })
                ->exists();
            
            return $exists;
        } catch (\Throwable $e) {
            // Ignore if UserPackage model doesn't exist or other errors
            return false;
        }
    }

    /**
     * Admin and superadmin accounts are treated as verified globally.
     */
    public function hasVerifiedEmail(): bool
    {
        if (($this->is_admin ?? false) || ($this->is_superadmin ?? false)) {
            return true;
        }

        return ! is_null($this->email_verified_at);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_superadmin' => 'boolean',
        ];
    }
}
