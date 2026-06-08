<?php

namespace App\Http\Controllers;

use App\Models\BlockedDate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class BlockedDateController extends Controller
{
    public function index()
    {
        $blocked = BlockedDate::orderBy('blocked_date', 'desc')->get(['id', 'blocked_date', 'end_date', 'reason', 'type']);
        return response()->json($blocked);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'blocked_date' => ['required', 'date', Rule::unique('blocked_dates', 'blocked_date')],
            'end_date' => 'required|date|after_or_equal:blocked_date',
            'reason' => 'nullable|string|max:255',
            'type' => ['nullable', Rule::in(['blocked', 'maintenance', 'holiday'])],
        ]);

        $startDate = Carbon::parse($data['blocked_date'])->toDateString();
        $endDate = Carbon::parse($data['end_date'])->toDateString();

        BlockedDate::create([
            'blocked_date' => $startDate,
            'end_date' => $endDate,
            'reason' => $data['reason'] ?? null,
            'type' => $data['type'] ?? 'blocked',
        ]);

        return redirect()->route('admin.manual_booking')->with('success', 'Blocked date range successfully saved.');
    }

    public function update(Request $request, BlockedDate $blockedDate)
    {
        $data = $request->validate([
            'blocked_date' => ['required', 'date', Rule::unique('blocked_dates', 'blocked_date')->ignore($blockedDate->id)],
            'end_date' => 'required|date|after_or_equal:blocked_date',
            'reason' => 'nullable|string|max:255',
            'type' => ['nullable', Rule::in(['blocked', 'maintenance', 'holiday'])],
        ]);

        $blockedDate->update([
            'blocked_date' => Carbon::parse($data['blocked_date'])->toDateString(),
            'end_date' => Carbon::parse($data['end_date'])->toDateString(),
            'reason' => $data['reason'] ?? null,
            'type' => $data['type'] ?? 'blocked',
        ]);

        return redirect()->route('admin.manual_booking')->with('success', 'Blocked date range successfully updated.');
    }

    public function destroy(BlockedDate $blockedDate)
    {
        $blockedDate->delete();
        return redirect()->route('admin.manual_booking')->with('success', 'Blocked date range successfully deleted.');
    }
}
