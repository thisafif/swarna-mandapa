<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Admin extends Model
{
    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password'];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public static function authenticate($email, $password)
    {
        $admin = self::where('email', $email)->first();
        if ($admin && Hash::check($password, $admin->password)) {
            return $admin;
        }
        return null;
    }
}
