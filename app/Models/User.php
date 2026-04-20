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
    ];

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

    /**
     * Check whether user should be allowed to enter LMS course pages.
     */
    public function hasLmsAccess(): bool
    {
        if (! empty($this->package_id)) {
            return true;
        }

        try {
            $hasHistoricalPackage = \App\Models\UserPackage::where('user_id', $this->id)->exists();
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
                $coachingSlug = config('coaching.coaching_package_slug', 'coaching-ticket');

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

                // Last-resort fallback: there is a successful payment but package link is missing.
                // Allow LMS to prevent paid users from being stuck while logs help reconciliation.
                try {
                    \Illuminate\Support\Facades\Log::warning('LMS access granted via successful transaction fallback without package link', [
                        'user_id' => $this->id,
                        'order_id' => optional($successfulTxns->first())->order_id,
                    ]);
                } catch (\Throwable $e) {
                    // ignore log failures
                }

                return true;
            }
        } catch (\Throwable $e) {
            // ignore and return false below
        }

        return false;
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
