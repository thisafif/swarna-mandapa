<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    // Edit promo
    public function edit(Promo $promo)
    {
        return response()->json([
            'id' => $promo->id,
            'code' => $promo->code,
            'name' => $promo->name,
            'discount_percent' => $promo->discount_percent,
            'valid_from' => optional($promo->valid_from)->toDateString(),
            'valid_until' => optional($promo->valid_until)->toDateString(),
            'is_active' => $promo->is_active,
        ]);
    }

    // Update promo
    public function update(Request $request, Promo $promo)
    {
        $request->validate([
            'promo_name' => 'required|string|max:100',
            'discount_percent' => 'required|numeric|min:1|max:100',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
            'promo_status' => 'required|in:active,inactive',
        ]);

        $promo->update([
            'name' => $request->promo_name,
            'discount_percent' => $request->discount_percent,
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'is_active' => $request->promo_status === 'active',
        ]);

        return redirect()->route('admin.villa_settings')->with('success', "Promo '{$promo->code}' berhasil diupdate!");
    }

    // Delete promo
    public function destroy(Promo $promo)
    {
        $code = $promo->code;
        $promo->delete();

        return redirect()->route('admin.villa_settings')->with('success', "Promo '{$code}' berhasil dihapus!");
    }
}
