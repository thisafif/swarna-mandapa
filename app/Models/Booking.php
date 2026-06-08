<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    protected $fillable = [
        'booking_code', 'check_in', 'check_out', 'guests',
        'first_name', 'last_name', 'email', 'phone', 'country',
        'promo_code', 'price_per_night', 'discount_amount', 'total_price',
        'nightly_price_breakdown',
        'status', 'is_manual', 'payment_ref', 'expires_at', 'notes',
        'payment_order_id', 'payment_id', 'paid_at',
    ];

    protected $casts = [
        'check_in'   => 'date',
        'check_out'  => 'date',
        'expires_at' => 'datetime',
        'is_manual'  => 'boolean',
        'nightly_price_breakdown' => 'array',
    ];

    // Generate kode booking unik, e.g. SWA-20250513-A3X9
    public static function generateCode(): string
    {
        do {
            $code = 'SWA-' . now()->format('Ymd') . '-' . strtoupper(\Str::random(4));
        } while (self::where('booking_code', $code)->exists());

        return $code;
    }

    // Cek apakah booking sudah expired (1 jam tidak bayar)
    public function isExpired(): bool
    {
        return $this->expires_at && now()->isAfter($this->expires_at);
    }

    // Jumlah malam
    public function getNightsAttribute(): int
    {
        return $this->check_in->diffInDays($this->check_out);
    }

    // Nama lengkap
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
