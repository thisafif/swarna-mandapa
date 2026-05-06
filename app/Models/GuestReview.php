<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestReview extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'review',
        'rating',
        'status'
    ];

    // Tambahkan ini agar pemanggilan {$review->full_name} di Controller tidak error
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}