<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@swarna-mandapa.com'],
            [
                'name' => 'Swarna Admin',
                'email' => 'admin@swarna-mandapa.com',
                'password' => 'SwarnaAdmin2024!',
            ]
        );
    }
}
