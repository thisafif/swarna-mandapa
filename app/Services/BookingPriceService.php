<?php

namespace App\Services;

use App\Models\Promo;
use App\Models\VillaPrice;
use Carbon\Carbon;
use RuntimeException;

class BookingPriceService
{
    public function getNightlyBreakdown(string $checkIn, string $checkOut): array
    {
        $start = Carbon::parse($checkIn)->startOfDay();
        $end = Carbon::parse($checkOut)->startOfDay();

        if ($end->lte($start)) {
            throw new RuntimeException('Check-out date must be after check-in date.');
        }

        $breakdown = [];
        $night = $start->copy();

        while ($night->lt($end)) {
            $date = $night->toDateString();
            $price = $this->findSeasonalPriceForDate($date)
                ?? $this->findBasePriceForDate($date);

            if (! $price) {
                throw new RuntimeException("The active villa price has not been configured for {$date}.");
            }

            $breakdown[] = [
                'date' => $date,
                'label' => $price->label ?: 'Villa Rate',
                'price' => (int) round((float) $price->price_per_night),
            ];

            $night->addDay();
        }

        return $breakdown;
    }

    private function findSeasonalPriceForDate(string $date): ?VillaPrice
    {
        return VillaPrice::where('is_active', true)
            ->where(fn ($query) => $query
                ->where('label', '!=', 'Base Price')
                ->orWhereNull('label'))
            ->whereDate('valid_from', '<=', $date)
            ->where(function ($query) use ($date) {
                $query->whereNull('valid_until')
                    ->orWhereDate('valid_until', '>=', $date);
            })
            ->orderByDesc('valid_from')
            ->first();
    }

    private function findBasePriceForDate(string $date): ?VillaPrice
    {
        return VillaPrice::where('is_active', true)
            ->where('label', 'Base Price')
            ->whereDate('valid_from', '<=', $date)
            ->where(function ($query) use ($date) {
                $query->whereNull('valid_until')
                    ->orWhereDate('valid_until', '>=', $date);
            })
            ->orderByDesc('valid_from')
            ->first();
    }

    public function calculate(string $checkIn, string $checkOut, ?string $promoCode = null): array
    {
        $nightlyBreakdown = $this->getNightlyBreakdown($checkIn, $checkOut);
        $nights = count($nightlyBreakdown);

        if ($nights < 1) {
            throw new RuntimeException('Booking must contain at least one night.');
        }

        $subtotal = array_sum(array_column($nightlyBreakdown, 'price'));
        $discount = 0;
        $validPromoCode = null;

        if ($promoCode !== null && trim($promoCode) !== '') {
            $checkInDate = Carbon::parse($checkIn)->toDateString();
            $promo = Promo::where('code', strtoupper(trim($promoCode)))
                ->where('is_active', true)
                ->whereDate('valid_from', '<=', $checkInDate)
                ->whereDate('valid_until', '>=', $checkInDate)
                ->first();

            if ($promo) {
                $discount = (int) round($subtotal * ((float) $promo->discount_percent / 100));
                $validPromoCode = $promo->code;
            }
        }

        $total = max(0, $subtotal - $discount);

        return [
            'nights' => $nights,
            'price_per_night' => round($subtotal / $nights, 2),
            'subtotal' => (int) round($subtotal),
            'discount_amount' => $discount,
            'total_price' => $total,
            'promo_code' => $validPromoCode,
            'nightly_breakdown' => $nightlyBreakdown,
        ];
    }
}
