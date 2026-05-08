<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoachingWarrantyTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'booking_id',
        'ticket_id',
        'downtime_minutes',
        'warranty_minutes',
        'status',
        'source',
        'issued_at',
        'used_at',
        'expires_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
        'downtime_minutes' => 'integer',
        'warranty_minutes' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(CoachingBooking::class, 'booking_id');
    }

    public function ticket()
    {
        return $this->belongsTo(CoachingTicket::class, 'ticket_id');
    }
}
