<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'first_name', 'last_name',
        'email', 'review', 'rating', 'status',
    ];

    // Hanya review yg disetujui (untuk landing page)
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Hanya review pending (untuk admin)
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Nama lengkap: "Budi Santoso"
    public function getFullNameAttribute(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    // Inisial untuk avatar: "BS"
    public function getInitialsAttribute(): string
    {
        return strtoupper(
            substr($this->first_name, 0, 1).
            substr($this->last_name, 0, 1)
        );
    }
}