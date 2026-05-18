<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = ['code', 'name', 'discount_percent', 'valid_from', 'valid_until', 'is_active'];
    protected $casts = ['valid_from' => 'date', 'valid_until' => 'date', 'is_active' => 'boolean'];
}