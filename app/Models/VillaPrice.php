<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class VillaPrice extends Model
{
    protected $fillable = ['price_per_night', 'valid_from', 'valid_until', 'label', 'is_active'];
    protected $casts = ['valid_from' => 'date', 'valid_until' => 'date', 'is_active' => 'boolean'];
}