<?php

namespace App\Http\Controllers;

use App\Models\VillaPrice;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VillaPriceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $this->validatePrice($request);
        $this->ensureNoActiveOverlap($validated);

        VillaPrice::create($validated);

        return redirect()->route('admin.villa_settings')->with('success', 'Seasonal price successfully saved!');
    }

    public function edit(VillaPrice $villaPrice)
    {
        return response()->json([
            'id' => $villaPrice->id,
            'label' => $villaPrice->label,
            'price_per_night' => (float) $villaPrice->price_per_night,
            'valid_from' => optional($villaPrice->valid_from)->toDateString(),
            'valid_until' => optional($villaPrice->valid_until)->toDateString(),
            'is_active' => $villaPrice->is_active,
        ]);
    }

    public function update(Request $request, VillaPrice $villaPrice)
    {
        $validated = $this->validatePrice($request);
        $this->ensureNoActiveOverlap($validated, $villaPrice);

        $villaPrice->update($validated);

        return redirect()->route('admin.villa_settings')->with('success', 'Seasonal price successfully updated!');
    }

    public function destroy(VillaPrice $villaPrice)
    {
        $villaPrice->delete();

        return redirect()->route('admin.villa_settings')->with('success', 'Seasonal price successfully deleted!');
    }

    private function validatePrice(Request $request): array
    {
        $validated = $request->validate([
            'label' => 'nullable|string|max:100',
            'price_per_night' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['label'] = $validated['label'] ?? null;
        $validated['is_active'] = $request->boolean('is_active');
        $validated['valid_from'] = CarbonImmutable::parse($validated['valid_from'])->toDateString();
        $validated['valid_until'] = ! empty($validated['valid_until'])
            ? CarbonImmutable::parse($validated['valid_until'])->toDateString()
            : null;

        return $validated;
    }

    private function ensureNoActiveOverlap(array $data, ?VillaPrice $ignore = null): void
    {
        if (! $data['is_active'] || $this->isBasePrice($data)) {
            return;
        }

        $startDate = CarbonImmutable::parse($data['valid_from'])->toDateString();
        $endDate = $data['valid_until']
            ? CarbonImmutable::parse($data['valid_until'])->toDateString()
            : CarbonImmutable::create(9999, 12, 31)->toDateString();

        $overlap = VillaPrice::where('is_active', true)
            ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->getKey()))
            ->where(fn ($query) => $query
                ->where('label', '!=', 'Base Price')
                ->orWhereNull('label'))
            ->whereDate('valid_from', '<=', $endDate)
            ->where(function ($query) use ($startDate) {
                $query->whereNull('valid_until')
                    ->orWhereDate('valid_until', '>=', $startDate);
            })
            ->first();

        if ($overlap) {
            throw ValidationException::withMessages([
                'valid_from' => 'Seasonal price period overlaps with an existing active seasonal price.',
            ]);
        }
    }

    private function isBasePrice(array $data): bool
    {
        return ($data['label'] ?? null) === 'Base Price' && $data['valid_until'] === null;
    }
}
