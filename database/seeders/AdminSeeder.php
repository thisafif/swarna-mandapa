<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('INITIAL_ADMIN_EMAIL');
        $password = env('INITIAL_ADMIN_PASSWORD');

        if (! $email || ! $password) {
            $this->command?->warn('INITIAL_ADMIN_EMAIL and INITIAL_ADMIN_PASSWORD are not set. Admin seeding skipped.');
            return;
        }

        $admin = Admin::firstOrNew(['email' => $email]);
        $admin->name = env('INITIAL_ADMIN_NAME', $admin->name ?: 'Swarna Admin');

        if (! $admin->exists) {
            $admin->password = $password;
        }

        $admin->save();
    }
}
