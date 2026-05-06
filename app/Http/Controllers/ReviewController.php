<?php

namespace App\Http\Controllers;

use App\Models\GuestReview;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // ─── PUBLIC ──────────────────────────────────────────────

    /**
     * GET /reviews — tampilkan review yang approved
     */
    public function index()
    {
        $testimonials = GuestReview::approved()->latest()->get();
        return view('reviews', compact('testimonials'));
    }

    /**
     * POST /reviews — simpan review baru (status: pending)
     */
    public function store(Request $request)
    {
    $validated = $request->validate([
        'first_name' => 'required|string|max:100',
        'last_name'  => 'required|string|max:100',
        'email'      => 'required|email|max:255',
        'review'     => 'required|string|max:2000',
        'rating'     => 'required|integer|min:1|max:5',
    ]);

    // Sekarang kolom 'first_name' dan 'last_name' sudah ada di DB
    GuestReview::create($validated);

    return response()->json(['message' => 'Review submitted successfully.'], 201);
    }

    // ─── ADMIN ───────────────────────────────────────────────

    /**
     * GET /admin/reviews
     */
    public function adminIndex(Request $request)
    {
        $filter = $request->query('filter', 'all');

        $reviews = GuestReview::when($filter !== 'all', fn($q) => $q->where('status', $filter))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $counts = [
            'all'      => GuestReview::count(),
            'pending'  => GuestReview::where('status', 'pending')->count(),
            'approved' => GuestReview::where('status', 'approved')->count(),
            'rejected' => GuestReview::where('status', 'rejected')->count(),
        ];

        return view('admin.testimoni.admin_reviews', compact('reviews', 'filter', 'counts'));
    }

    /**
     * PATCH /admin/reviews/{review}/approve
     */
    public function approve(GuestReview $review)
    {
        $review->update(['status' => 'approved']);
        return back()->with('success', "Review by {$review->full_name} has been approved.");
    }

    /**
     * PATCH /admin/reviews/{review}/reject
     */
    public function reject(GuestReview $review)
    {
        $review->update(['status' => 'rejected']);
        return back()->with('success', "Review by {$review->full_name} has been rejected.");
    }

    /**
     * DELETE /admin/reviews/{review}
     */
    public function destroy(GuestReview $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }
}