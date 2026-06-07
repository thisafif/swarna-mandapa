<?php

namespace Tests\Feature;

use App\Http\Controllers\PaymentController;
use App\Models\Admin;
use App\Models\BlockedDate;
use App\Models\Booking;
use App\Models\Promo;
use App\Models\VillaPrice;
use Database\Seeders\AdminSeeder;
use Database\Seeders\PromoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BusinessRiskTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        putenv('INITIAL_ADMIN_EMAIL');
        putenv('INITIAL_ADMIN_PASSWORD');
        putenv('INITIAL_ADMIN_NAME');
        putenv('SEED_DEMO_PROMOS');
        putenv('DOKU_BASE_URL');
        putenv('DOKU_CLIENT_ID');
        putenv('DOKU_SECRET_KEY');

        parent::tearDown();
    }

    public function test_admin_seeder_does_not_reset_existing_password(): void
    {
        putenv('INITIAL_ADMIN_EMAIL=owner@example.com');
        putenv('INITIAL_ADMIN_PASSWORD=new-secret-password');
        putenv('INITIAL_ADMIN_NAME=Owner');

        Admin::create([
            'name' => 'Existing Owner',
            'email' => 'owner@example.com',
            'password' => 'old-secret-password',
        ]);

        $this->seed(AdminSeeder::class);

        $admin = Admin::where('email', 'owner@example.com')->firstOrFail();
        $this->assertTrue(Hash::check('old-secret-password', $admin->password));
        $this->assertFalse(Hash::check('new-secret-password', $admin->password));
    }

    public function test_promo_seeder_skips_production_and_does_not_override_promos(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        putenv('SEED_DEMO_PROMOS=true');

        Promo::create([
            'code' => 'WELCOME10',
            'name' => 'Business Promo',
            'discount_percent' => 3,
            'valid_from' => '2026-01-01',
            'valid_until' => '2026-12-31',
            'is_active' => false,
        ]);

        (new PromoSeeder)->run();

        $promo = Promo::where('code', 'WELCOME10')->firstOrFail();
        $this->assertSame('Business Promo', $promo->name);
        $this->assertEquals(3, $promo->discount_percent);
        $this->assertFalse($promo->is_active);
    }

    public function test_booking_is_rejected_when_date_overlaps_blocked_date(): void
    {
        VillaPrice::create([
            'price_per_night' => 7000000,
            'valid_from' => today()->toDateString(),
            'valid_until' => null,
            'is_active' => true,
        ]);

        BlockedDate::create([
            'blocked_date' => today()->addDays(3)->toDateString(),
            'end_date' => today()->addDays(4)->toDateString(),
            'reason' => 'Maintenance',
            'type' => 'maintenance',
        ]);

        $response = $this->post(route('booking.store'), $this->bookingPayload([
            'check_in' => today()->addDays(2)->toDateString(),
            'check_out' => today()->addDays(5)->toDateString(),
        ]));

        $response->assertSessionHasErrors('check_in');
        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_booking_fails_when_active_villa_price_is_missing(): void
    {
        $response = $this->post(route('booking.store'), $this->bookingPayload([
            'check_in' => today()->addDays(2)->toDateString(),
            'check_out' => today()->addDays(4)->toDateString(),
        ]));

        $response->assertSessionHasErrors('check_in');
        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_booking_uses_active_villa_price(): void
    {
        VillaPrice::create([
            'price_per_night' => 7500000,
            'valid_from' => today()->toDateString(),
            'valid_until' => null,
            'is_active' => true,
        ]);

        $response = $this->post(route('booking.store'), $this->bookingPayload([
            'check_in' => today()->addDays(2)->toDateString(),
            'check_out' => today()->addDays(4)->toDateString(),
        ]));

        $response->assertRedirect(route('booking.confirmation'));

        $booking = session('booking');
        $this->assertSame(7500000.0, $booking['price_per_night']);
        $this->assertSame(15000000.0, $booking['subtotal']);
    }

    public function test_manual_booking_succeeds_without_villa_price_and_stores_zero_prices(): void
    {
        $response = $this
            ->withSession(['admin_authenticated' => true])
            ->post(route('admin.manual_booking.submit'), $this->manualBookingPayload([
                'check_in' => today()->addDays(8)->toDateString(),
                'check_out' => today()->addDays(10)->toDateString(),
            ]));

        $response->assertRedirect(route('admin.booking_list'));

        $booking = Booking::firstOrFail();
        $this->assertSame(0.0, (float) $booking->price_per_night);
        $this->assertSame(0.0, (float) $booking->discount_amount);
        $this->assertSame(0.0, (float) $booking->total_price);
        $this->assertSame('CONFIRMED', $booking->status);
        $this->assertTrue($booking->is_manual);
    }

    public function test_manual_booking_is_rejected_when_date_overlaps_blocked_date(): void
    {
        BlockedDate::create([
            'blocked_date' => today()->addDays(8)->toDateString(),
            'end_date' => today()->addDays(9)->toDateString(),
            'reason' => 'Owner stay',
            'type' => 'blocked',
        ]);

        $response = $this
            ->withSession(['admin_authenticated' => true])
            ->post(route('admin.manual_booking.submit'), $this->manualBookingPayload([
                'check_in' => today()->addDays(7)->toDateString(),
                'check_out' => today()->addDays(10)->toDateString(),
            ]));

        $response->assertSessionHasErrors('check_in');
        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_active_promo_can_be_updated(): void
    {
        $promo = Promo::create([
            'code' => 'ACTIVE20',
            'name' => 'Active Promo',
            'discount_percent' => 20,
            'valid_from' => today()->subDay()->toDateString(),
            'valid_until' => today()->addDays(10)->toDateString(),
            'is_active' => true,
        ]);

        $response = $this
            ->withSession(['admin_authenticated' => true])
            ->put(route('admin.promo.update', $promo), [
                'promo_name' => 'Updated Active Promo',
                'discount_percent' => 25,
                'valid_from' => today()->toDateString(),
                'valid_until' => today()->addDays(14)->toDateString(),
                'promo_status' => 'inactive',
            ]);

        $response->assertRedirect(route('admin.villa_settings'));

        $promo->refresh();
        $this->assertSame('Updated Active Promo', $promo->name);
        $this->assertEquals(25, $promo->discount_percent);
        $this->assertSame(today()->toDateString(), $promo->valid_from->toDateString());
        $this->assertSame(today()->addDays(14)->toDateString(), $promo->valid_until->toDateString());
        $this->assertFalse($promo->is_active);
    }

    public function test_active_promo_can_be_deleted(): void
    {
        $promo = Promo::create([
            'code' => 'DELETE20',
            'name' => 'Delete Promo',
            'discount_percent' => 20,
            'valid_from' => today()->subDay()->toDateString(),
            'valid_until' => today()->addDays(10)->toDateString(),
            'is_active' => true,
        ]);

        $response = $this
            ->withSession(['admin_authenticated' => true])
            ->delete(route('admin.promo.destroy', $promo));

        $response->assertRedirect(route('admin.villa_settings'));
        $this->assertDatabaseMissing('promos', ['code' => 'DELETE20']);
    }

    public function test_doku_config_fails_fast_in_production_when_sandbox_is_configured(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        putenv('DOKU_BASE_URL=https://api-sandbox.doku.com');
        putenv('DOKU_CLIENT_ID=prod-client');
        putenv('DOKU_SECRET_KEY=prod-secret');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Konfigurasi DOKU production');

        app(PaymentController::class);
    }

    private function bookingPayload(array $overrides = []): array
    {
        return array_merge([
            'check_in' => today()->addDays(2)->toDateString(),
            'check_out' => today()->addDays(4)->toDateString(),
            'guests' => 2,
            'first_name' => 'Test',
            'last_name' => 'Guest',
            'email' => 'guest@example.com',
            'phone' => '+6281234567890',
            'country' => 'ID',
        ], $overrides);
    }

    private function manualBookingPayload(array $overrides = []): array
    {
        return array_merge([
            'check_in' => today()->addDays(8)->toDateString(),
            'check_out' => today()->addDays(10)->toDateString(),
            'guest_name' => 'Manual Guest',
            'phone' => '+6281234567890',
            'guests' => 2,
            'email' => 'manual@example.com',
            'notes' => 'Offline booking',
        ], $overrides);
    }
}
