<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promo;

class PromoSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production') || ! filter_var(env('SEED_DEMO_PROMOS', false), FILTER_VALIDATE_BOOLEAN)) {
            return;
        }

        // // Default promos untuk booking
        // $promos = [
        //     [
        //         'code' => 'WELCOME10',
        //         'name' => 'Welcome Discount',
        //         'discount_percent' => 10.00,
        //         'valid_from' => date('Y-01-01'),
        //         'valid_until' => date('Y-12-31'),
        //         'is_active' => true,
        //     ],
        //     [
        //         'code' => 'SWARNA20',
        //         'name' => 'Swarna Special',
        //         'discount_percent' => 20.00,
        //         'valid_from' => date('Y-01-01'),
        //         'valid_until' => date('Y-12-31'),
        //         'is_active' => true,
        //     ],
        //     [
        //         'code' => 'BALI15',
        //         'name' => 'Bali Promotion',
        //         'discount_percent' => 15.00,
        //         'valid_from' => date('Y-01-01'),
        //         'valid_until' => date('Y-12-31'),
        //         'is_active' => true,
        //     ],
        // ];

        foreach ($promos as $promo) {
            Promo::updateOrCreate(['code' => $promo['code']], $promo);
        }
    }
}
