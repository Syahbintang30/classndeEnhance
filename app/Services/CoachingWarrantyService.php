<?php

namespace App\Services;

use App\Models\CoachingBooking;
use App\Models\CoachingWarrantyTicket;

class CoachingWarrantyService
{
    public function issueFromBooking(CoachingBooking $booking, int $warrantyMinutes, string $source = 'admin'): CoachingWarrantyTicket
    {
        $existing = CoachingWarrantyTicket::where('booking_id', $booking->id)->latest()->first();
        if ($existing) {
            $minutes = max(0, (int) $warrantyMinutes);
            $status = $minutes > 0 ? 'available' : 'rejected';
            $existing->warranty_minutes = $minutes > 0 ? $minutes : null;
            $existing->status = $status;
            $existing->source = $source;
            $existing->issued_at = now();
            $existing->save();
            return $existing;
        }

        $minutes = max(0, (int) $warrantyMinutes);
        $status = $minutes > 0 ? 'available' : 'rejected';

        return CoachingWarrantyTicket::create([
            'user_id' => $booking->user_id,
            'booking_id' => $booking->id,
            'ticket_id' => $booking->ticket_id,
            'warranty_minutes' => $minutes > 0 ? $minutes : null,
            'status' => $status,
            'source' => $source,
            'issued_at' => now(),
        ]);
    }
}