<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedDate extends Model
{
    use HasFactory;

    protected $fillable = ['blocked_date', 'end_date', 'reason', 'type'];

    protected $casts = [
        'blocked_date' => 'date',
        'end_date' => 'date',
    ];
}
