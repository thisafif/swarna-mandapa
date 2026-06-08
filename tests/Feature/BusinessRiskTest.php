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
        $this->assertSame(15000000, $booking['subtotal']);
        $this->assertCount(2, $booking['nightly_breakdown']);
    }

    public function test_booking_calculates_each_night_across_seasons(): void
    {
        $normalFrom = today()->toDateString();
        $highFrom = today()->addDays(2)->toDateString();
        $highUntil = today()->addDays(3)->toDateString();

        VillaPrice::create([
            'label' => 'Normal Season',
            'price_per_night' => 4500000,
            'valid_from' => $normalFrom,
            'valid_until' => null,
            'is_active' => true,
        ]);
        VillaPrice::create([
            'label' => 'High Season',
            'price_per_night' => 5500000,
            'valid_from' => $highFrom,
            'valid_until' => $highUntil,
            'is_active' => true,
        ]);

        $response = $this->post(route('booking.store'), $this->bookingPayload([
            'check_in' => today()->addDays(2)->toDateString(),
            'check_out' => today()->addDays(5)->toDateString(),
        ]));

        $response->assertRedirect(route('booking.confirmation'));

        $booking = session('booking');
        $this->assertSame(15500000, $booking['subtotal']);
        $this->assertSame(5166666.67, $booking['price_per_night']);
        $this->assertSame(['High Season', 'High Season', 'Normal Season'], array_column($booking['nightly_breakdown'], 'label'));
    }

    public function test_booking_applies_promo_to_seasonal_subtotal_and_stores_breakdown(): void
    {
        VillaPrice::create([
            'label' => 'Normal Season',
            'price_per_night' => 4500000,
            'valid_from' => today()->toDateString(),
            'valid_until' => null,
            'is_active' => true,
        ]);
        VillaPrice::create([
            'label' => 'High Season',
            'price_per_night' => 5500000,
            'valid_from' => today()->addDays(2)->toDateString(),
            'valid_until' => today()->addDays(2)->toDateString(),
            'is_active' => true,
        ]);
        Promo::create([
            'code' => 'WELCOME10',
            'name' => 'Welcome',
            'discount_percent' => 10,
            'valid_from' => today()->subDay()->toDateString(),
            'valid_until' => today()->addDays(10)->toDateString(),
            'is_active' => true,
        ]);

        $this->post(route('booking.store'), $this->bookingPayload([
            'check_in' => today()->addDays(2)->toDateString(),
            'check_out' => today()->addDays(4)->toDateString(),
            'promo_code' => 'WELCOME10',
        ]))->assertRedirect(route('booking.confirmation'));

        $bookingSession = session('booking');
        $this->assertSame(10000000, $bookingSession['subtotal']);
        $this->assertSame(1000000, $bookingSession['discount_amount']);
        $this->assertSame(9000000, $bookingSession['total_price']);

        $this->post(route('booking.confirmation.store'), [
            'terms_of_rent' => '1',
        ])
            ->assertRedirect(route('booking.invoice'));

        $booking = Booking::firstOrFail();
        $this->assertSame('WELCOME10', $booking->promo_code);
        $this->assertSame(9000000.0, (float) $booking->total_price);
        $this->assertCount(2, $booking->nightly_price_breakdown);
        $this->assertSame('High Season', $booking->nightly_price_breakdown[0]['label']);
    }

    public function test_booking_price_preview_returns_seasonal_breakdown(): void
    {
        VillaPrice::create([
            'label' => 'Normal Season',
            'price_per_night' => 4500000,
            'valid_from' => today()->toDateString(),
            'valid_until' => null,
            'is_active' => true,
        ]);
        VillaPrice::create([
            'label' => 'High Season',
            'price_per_night' => 5500000,
            'valid_from' => today()->addDays(2)->toDateString(),
            'valid_until' => today()->addDays(2)->toDateString(),
            'is_active' => true,
        ]);

        $response = $this->postJson(route('booking.pricePreview'), [
            'check_in' => today()->addDays(2)->toDateString(),
            'check_out' => today()->addDays(4)->toDateString(),
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('nights', 2)
            ->assertJsonPath('subtotal', 10000000)
            ->assertJsonPath('nightly_breakdown.0.label', 'High Season')
            ->assertJsonPath('nightly_breakdown.1.label', 'Normal Season');
    }

    public function test_legacy_overlapping_direct_price_rows_use_latest_valid_from(): void
    {
        VillaPrice::create([
            'label' => 'Base Price',
            'price_per_night' => 4500000,
            'valid_from' => today()->toDateString(),
            'valid_until' => null,
            'is_active' => true,
        ]);
        VillaPrice::create([
            'label' => 'Older Holiday',
            'price_per_night' => 5500000,
            'valid_from' => today()->addDays(2)->toDateString(),
            'valid_until' => today()->addDays(10)->toDateString(),
            'is_active' => true,
        ]);
        VillaPrice::create([
            'label' => 'Latest Peak',
            'price_per_night' => 6500000,
            'valid_from' => today()->addDays(5)->toDateString(),
            'valid_until' => today()->addDays(8)->toDateString(),
            'is_active' => true,
        ]);

        $response = $this->postJson(route('booking.pricePreview'), [
            'check_in' => today()->addDays(4)->toDateString(),
            'check_out' => today()->addDays(7)->toDateString(),
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('subtotal', 18500000)
            ->assertJsonPath('nightly_breakdown.0.label', 'Older Holiday')
            ->assertJsonPath('nightly_breakdown.1.label', 'Latest Peak')
            ->assertJsonPath('nightly_breakdown.2.label', 'Latest Peak')
            ->assertJsonPath('nightly_breakdown.1.price', 6500000);
    }

    public function test_booking_price_preview_fails_when_a_night_has_no_price(): void
    {
        VillaPrice::create([
            'label' => 'Configured Night',
            'price_per_night' => 4500000,
            'valid_from' => today()->addDays(2)->toDateString(),
            'valid_until' => today()->addDays(2)->toDateString(),
            'is_active' => true,
        ]);

        $missingDate = today()->addDays(3)->toDateString();
        $response = $this->postJson(route('booking.pricePreview'), [
            'check_in' => today()->addDays(2)->toDateString(),
            'check_out' => today()->addDays(4)->toDateString(),
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', "The active villa price has not been configured for {$missingDate}.");
    }

    public function test_calendar_data_returns_pending_and_confirmed_booking_details(): void
    {
        $pending = Booking::create([
            'booking_code' => 'SWA-20260710-PEND',
            'check_in' => '2026-07-10',
            'check_out' => '2026-07-12',
            'guests' => 3,
            'first_name' => 'Pending',
            'last_name' => 'Guest',
            'email' => 'pending@example.com',
            'phone' => '+628111111111',
            'country' => 'ID',
            'promo_code' => 'SUMMER10',
            'price_per_night' => 5000000,
            'nightly_price_breakdown' => [
                ['date' => '2026-07-10', 'label' => 'High Season', 'price' => 5500000],
                ['date' => '2026-07-11', 'label' => 'Normal Season', 'price' => 4500000],
            ],
            'discount_amount' => 1000000,
            'total_price' => 9000000,
            'status' => 'PENDING',
            'is_manual' => false,
            'expires_at' => now()->addHour(),
        ]);

        Booking::create([
            'booking_code' => 'SWA-20260720-CONF',
            'check_in' => '2026-07-20',
            'check_out' => '2026-07-21',
            'guests' => 2,
            'first_name' => 'Confirmed',
            'last_name' => 'Guest',
            'email' => 'confirmed@example.com',
            'phone' => '+628222222222',
            'country' => 'ID',
            'price_per_night' => 4500000,
            'discount_amount' => 0,
            'total_price' => 4500000,
            'status' => 'CONFIRMED',
            'is_manual' => true,
        ]);

        $response = $this->getJson(route('booking.calendarData', [
            'year' => 2026,
            'month' => 7,
        ]));

        $response->assertOk()
            ->assertJsonPath('booked.2026-07-10.id', $pending->id)
            ->assertJsonPath('booked.2026-07-10.booking_code', 'SWA-20260710-PEND')
            ->assertJsonPath('booked.2026-07-10.status', 'PENDING')
            ->assertJsonPath('booked.2026-07-10.first_name', 'Pending')
            ->assertJsonPath('booked.2026-07-10.last_name', 'Guest')
            ->assertJsonPath('booked.2026-07-10.email', 'pending@example.com')
            ->assertJsonPath('booked.2026-07-10.phone', '+628111111111')
            ->assertJsonPath('booked.2026-07-10.check_in', '2026-07-10')
            ->assertJsonPath('booked.2026-07-10.check_out', '2026-07-12')
            ->assertJsonPath('booked.2026-07-10.guests', 3)
            ->assertJsonPath('booked.2026-07-10.price_per_night', 5000000)
            ->assertJsonPath('booked.2026-07-10.discount_amount', 1000000)
            ->assertJsonPath('booked.2026-07-10.total_price', 9000000)
            ->assertJsonPath('booked.2026-07-10.promo_code', 'SUMMER10')
            ->assertJsonPath('booked.2026-07-10.is_manual', false)
            ->assertJsonPath('booked.2026-07-10.nightly_price_breakdown.0.label', 'High Season')
            ->assertJsonPath('booked.2026-07-20.status', 'CONFIRMED')
            ->assertJsonPath('booked.2026-07-20.is_manual', true);
    }

    public function test_calendar_data_returns_blocked_date_reason_and_type(): void
    {
        BlockedDate::create([
            'blocked_date' => '2026-08-05',
            'end_date' => '2026-08-06',
            'reason' => 'Pool maintenance',
            'type' => 'maintenance',
        ]);

        $response = $this->getJson(route('booking.calendarData', [
            'year' => 2026,
            'month' => 8,
        ]));

        $response->assertOk()
            ->assertJsonPath('blocked.2026-08-05.status', 'MAINTENANCE')
            ->assertJsonPath('blocked.2026-08-05.type', 'maintenance')
            ->assertJsonPath('blocked.2026-08-05.reason', 'Pool maintenance')
            ->assertJsonPath('blocked.2026-08-06.status', 'MAINTENANCE');
    }

    public function test_calendar_data_does_not_include_cancelled_booking_as_booked(): void
    {
        Booking::create([
            'booking_code' => 'SWA-20260910-CANC',
            'check_in' => '2026-09-10',
            'check_out' => '2026-09-12',
            'guests' => 2,
            'first_name' => 'Cancelled',
            'last_name' => 'Guest',
            'email' => 'cancelled@example.com',
            'phone' => '+628333333333',
            'country' => 'ID',
            'price_per_night' => 4500000,
            'discount_amount' => 0,
            'total_price' => 9000000,
            'status' => 'CANCELLED',
        ]);

        $response = $this->getJson(route('booking.calendarData', [
            'year' => 2026,
            'month' => 9,
        ]));

        $response->assertOk();
        $this->assertArrayNotHasKey('2026-09-10', $response->json('booked'));
        $this->assertArrayNotHasKey('2026-09-11', $response->json('booked'));
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

    public function test_admin_can_manage_seasonal_prices_and_base_price_does_not_overwrite_them(): void
    {
        $response = $this
            ->withSession(['admin_authenticated' => true])
            ->post(route('admin.villa_prices.store'), [
                'label' => 'High Season',
                'price_per_night' => 5500000,
                'valid_from' => today()->addDays(10)->toDateString(),
                'valid_until' => today()->addDays(20)->toDateString(),
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('admin.villa_settings'));
        $price = VillaPrice::where('label', 'High Season')->firstOrFail();
        $this->assertTrue($price->is_active);

        $this->withSession(['admin_authenticated' => true])
            ->put(route('admin.villa_prices.update', $price), [
                'label' => 'Peak Season',
                'price_per_night' => 6500000,
                'valid_from' => today()->addDays(10)->toDateString(),
                'valid_until' => today()->addDays(20)->toDateString(),
                'is_active' => '0',
            ])
            ->assertRedirect(route('admin.villa_settings'));

        $price->refresh();
        $this->assertSame('Peak Season', $price->label);
        $this->assertEquals(6500000, $price->price_per_night);
        $this->assertFalse($price->is_active);

        $this->withSession(['admin_authenticated' => true])
            ->post(route('admin.villa_settings.base_price'), [
                'base_price' => 4500000,
            ])
            ->assertRedirect(route('admin.villa_settings'));

        $price->refresh();
        $this->assertSame('Peak Season', $price->label);
        $this->assertEquals(6500000, $price->price_per_night);
        $this->assertDatabaseHas('villa_prices', [
            'label' => 'Base Price',
            'price_per_night' => 4500000,
            'valid_until' => null,
            'is_active' => true,
        ]);

        $this->withSession(['admin_authenticated' => true])
            ->delete(route('admin.villa_prices.destroy', $price))
            ->assertRedirect(route('admin.villa_settings'));

        $this->assertDatabaseMissing('villa_prices', ['label' => 'Peak Season']);
    }

    public function test_admin_cannot_create_active_overlapping_seasonal_price(): void
    {
        VillaPrice::create([
            'label' => 'High Season',
            'price_per_night' => 5500000,
            'valid_from' => today()->addDays(10)->toDateString(),
            'valid_until' => today()->addDays(20)->toDateString(),
            'is_active' => true,
        ]);

        $response = $this
            ->withSession(['admin_authenticated' => true])
            ->post(route('admin.villa_prices.store'), [
                'label' => 'Peak Season',
                'price_per_night' => 6500000,
                'valid_from' => today()->addDays(15)->toDateString(),
                'valid_until' => today()->addDays(25)->toDateString(),
                'is_active' => '1',
            ]);

        $response->assertSessionHasErrors([
            'valid_from' => 'Seasonal price period overlaps with an existing active seasonal price.',
        ]);
        $this->assertDatabaseMissing('villa_prices', ['label' => 'Peak Season']);
    }

    public function test_admin_cannot_create_active_seasonal_price_on_inclusive_boundary(): void
    {
        VillaPrice::create([
            'label' => 'High Season',
            'price_per_night' => 5500000,
            'valid_from' => '2026-07-01',
            'valid_until' => '2026-07-10',
            'is_active' => true,
        ]);

        $response = $this
            ->withSession(['admin_authenticated' => true])
            ->post(route('admin.villa_prices.store'), [
                'label' => 'Peak Season',
                'price_per_night' => 6500000,
                'valid_from' => '2026-07-10',
                'valid_until' => '2026-07-20',
                'is_active' => '1',
            ]);

        $response->assertSessionHasErrors([
            'valid_from' => 'Seasonal price period overlaps with an existing active seasonal price.',
        ]);
        $this->assertDatabaseMissing('villa_prices', ['label' => 'Peak Season']);
    }

    public function test_admin_cannot_create_active_seasonal_price_after_open_ended_active_period(): void
    {
        VillaPrice::create([
            'label' => 'High Season',
            'price_per_night' => 5500000,
            'valid_from' => '2026-07-01',
            'valid_until' => null,
            'is_active' => true,
        ]);

        $response = $this
            ->withSession(['admin_authenticated' => true])
            ->post(route('admin.villa_prices.store'), [
                'label' => 'Peak Season',
                'price_per_night' => 6500000,
                'valid_from' => '2026-08-01',
                'valid_until' => '2026-08-10',
                'is_active' => '1',
            ]);

        $response->assertSessionHasErrors([
            'valid_from' => 'Seasonal price period overlaps with an existing active seasonal price.',
        ]);
        $this->assertDatabaseMissing('villa_prices', ['label' => 'Peak Season']);
    }

    public function test_admin_can_create_inactive_overlapping_seasonal_price(): void
    {
        VillaPrice::create([
            'label' => 'High Season',
            'price_per_night' => 5500000,
            'valid_from' => '2026-07-01',
            'valid_until' => '2026-07-10',
            'is_active' => true,
        ]);

        $response = $this
            ->withSession(['admin_authenticated' => true])
            ->post(route('admin.villa_prices.store'), [
                'label' => 'Draft Peak Season',
                'price_per_night' => 6500000,
                'valid_from' => '2026-07-05',
                'valid_until' => '2026-07-15',
                'is_active' => '0',
            ]);

        $response->assertRedirect(route('admin.villa_settings'));
        $this->assertDatabaseHas('villa_prices', [
            'label' => 'Draft Peak Season',
            'is_active' => false,
        ]);
    }

    public function test_admin_can_create_non_overlapping_active_seasonal_price_and_nullable_end_date(): void
    {
        VillaPrice::create([
            'label' => 'High Season',
            'price_per_night' => 5500000,
            'valid_from' => '2026-07-01',
            'valid_until' => '2026-07-10',
            'is_active' => true,
        ]);

        $response = $this
            ->withSession(['admin_authenticated' => true])
            ->post(route('admin.villa_prices.store'), [
                'label' => 'Peak Season',
                'price_per_night' => 6500000,
                'valid_from' => '2026-07-11',
                'valid_until' => null,
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('admin.villa_settings'));
        $price = VillaPrice::where('label', 'Peak Season')->firstOrFail();
        $this->assertSame('2026-07-11', $price->valid_from->toDateString());
        $this->assertNull($price->valid_until);
        $this->assertTrue($price->is_active);
    }

    public function test_admin_cannot_create_seasonal_price_with_end_before_start(): void
    {
        $response = $this
            ->withSession(['admin_authenticated' => true])
            ->post(route('admin.villa_prices.store'), [
                'label' => 'Peak Season',
                'price_per_night' => 6500000,
                'valid_from' => '2026-07-11',
                'valid_until' => '2026-07-10',
                'is_active' => '1',
            ]);

        $response->assertSessionHasErrors('valid_until');
        $this->assertDatabaseMissing('villa_prices', ['label' => 'Peak Season']);
    }

    public function test_admin_cannot_update_seasonal_price_to_overlap_active_period(): void
    {
        VillaPrice::create([
            'label' => 'High Season',
            'price_per_night' => 5500000,
            'valid_from' => today()->addDays(10)->toDateString(),
            'valid_until' => today()->addDays(20)->toDateString(),
            'is_active' => true,
        ]);

        $peak = VillaPrice::create([
            'label' => 'Peak Season',
            'price_per_night' => 6500000,
            'valid_from' => today()->addDays(25)->toDateString(),
            'valid_until' => today()->addDays(30)->toDateString(),
            'is_active' => true,
        ]);

        $response = $this
            ->withSession(['admin_authenticated' => true])
            ->put(route('admin.villa_prices.update', $peak), [
                'label' => 'Peak Season',
                'price_per_night' => 6500000,
                'valid_from' => today()->addDays(18)->toDateString(),
                'valid_until' => today()->addDays(28)->toDateString(),
                'is_active' => '1',
            ]);

        $response->assertSessionHasErrors([
            'valid_from' => 'Seasonal price period overlaps with an existing active seasonal price.',
        ]);
        $peak->refresh();
        $this->assertSame(today()->addDays(25)->toDateString(), $peak->valid_from->toDateString());
    }

    public function test_admin_can_update_seasonal_price_without_conflicting_with_itself(): void
    {
        $price = VillaPrice::create([
            'label' => 'High Season',
            'price_per_night' => 5500000,
            'valid_from' => '2026-07-01',
            'valid_until' => '2026-07-10',
            'is_active' => true,
        ]);

        $response = $this
            ->withSession(['admin_authenticated' => true])
            ->put(route('admin.villa_prices.update', $price), [
                'label' => 'High Season Updated',
                'price_per_night' => 5750000,
                'valid_from' => '2026-07-01',
                'valid_until' => '2026-07-10',
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('admin.villa_settings'));
        $price->refresh();
        $this->assertSame('High Season Updated', $price->label);
        $this->assertEquals(5750000, $price->price_per_night);
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
