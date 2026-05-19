@extends('layouts.admin')

@push('styles')
<style>
    /* Typography & Base */
    .page-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.2rem;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
        text-align: center;
    }
    .page-title em {
        font-style: italic;
        color: var(--brand-gold-dark);
    }
    .page-subtitle {
        text-align: center;
        font-size: 0.82rem;
        color: var(--text-muted);
        margin-bottom: 2.5rem;
    }

    /* Paksa Hapus Avatar Jika Masih Ada di Sistem */
    .guest-avatar, .avatar, [class*="avatar"] {
        display: none !important;
    }

    /* Statistik Horizontal */
    .stats-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #FFFFFF;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        margin-bottom: 2rem;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .stat-item { text-align: center; flex: 1; }
    .stat-item:not(:last-child) { border-right: 1px solid #EBEBEB; }
    .stat-label {
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }
    .stat-value { font-size: 1.5rem; font-weight: 700; color: var(--text-dark); }

    /* Table Styling */
    .widget-panel {
        background: #FFFFFF;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.05);
    }
    .reviews-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
    .reviews-table th {
        font-size: 0.7rem;
        color: var(--text-muted);
        font-weight: 700;
        text-transform: uppercase;
        padding-bottom: 1rem;
        border-bottom: 1px solid #EBEBEB;
    }
    .reviews-table td { padding: 1.2rem 0; border-bottom: 1px solid #F5F5F5; vertical-align: middle; }

    /* Filter Tabs */
    .filter-tabs { display: flex; gap: 0.5rem; margin-bottom: 1.5rem; border-bottom: 1px solid #EBEBEB; padding-bottom: 1rem; }
    .filter-tab {
        padding: 0.35rem 1rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600;
        text-decoration: none; color: #888; background: #F5F5F5;
    }
    .filter-tab.active { background: #A67C37; color: #fff; }

    /* Status Badges */
    .status-badge { padding: 3px 10px; border-radius: 999px; font-size: 0.7rem; font-weight: 700; }
    .status-pending { background: #FEF3C7; color: #92400E; }
    .status-approved { background: #DCFCE7; color: #166534; }
    .status-rejected { background: #FEE2E2; color: #991B1B; }

    .action-btns { display: flex; gap: 0.4rem; justify-content: flex-end; }
    .btn-action { width: 32px; height: 32px; border-radius: 50%; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; }
</style>
@endpush

@section('content')
    <h1 class="page-title">Guest <em>Reviews</em></h1>
    <p class="page-subtitle">Approve reviews to show them on the landing page, or reject to hide them.</p>

    {{-- Statistik --}}
    <div class="stats-container">
        <div class="stat-item">
            <div class="stat-label">Total</div>
            <div class="stat-value">{{ $counts['all'] }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Pending</div>
            <div class="stat-value text-warning">{{ $counts['pending'] }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Approved</div>
            <div class="stat-value text-success">{{ $counts['approved'] }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Rejected</div>
            <div class="stat-value text-danger">{{ $counts['rejected'] }}</div>
        </div>
    </div>

    <div class="widget-panel">
        <div class="filter-tabs">
            @foreach (['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $key => $label)
                <a href="{{ route('admin.reviews.index', ['filter' => $key]) }}" class="filter-tab {{ $filter === $key ? 'active' : '' }}">
                    {{ $label }} ({{ $counts[$key] }})
                </a>
            @endforeach
        </div>

        <div class="table-responsive">
            <table class="reviews-table">
                <thead>
                    <tr>
                        <th style="width: 250px;">Guest</th>
                        <th>Review Content</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reviews as $review)
                        <tr>
                            {{-- KOLOM GUEST BERSALIN --}}
                            <td>
                                <div style="display: block !important;">
                                    <div style="font-weight: 700; color: #333; font-size: 0.95rem;">
                                        {{ $review->full_name }}
                                    </div>
                                    <div style="color: #777; font-size: 0.8rem;">
                                        {{ $review->email }}
                                    </div>
                                    <div style="color: #999; font-size: 0.7rem; margin-top: 2px;">
                                        {{ $review->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div style="max-width: 400px; color: #444;">{{ $review->review }}</div>
                                <div class="text-warning mt-1">@for ($i = 0; $i < $review->rating; $i++) ★ @endfor</div>
                            </td>

                            <td>
                                <span class="status-badge status-{{ $review->status }}">{{ $review->status }}</span>
                            </td>

                            <td>
                                <div class="action-btns">
                                    @if ($review->status !== 'approved')
                                        <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn-action" style="background:#DCFCE7; color:#166534;">✓</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action" style="background:#F5F5F5; color:#888;">🗑</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $reviews->links() }}</div>
    </div>
@endsection