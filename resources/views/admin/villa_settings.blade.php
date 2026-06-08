@extends('layouts.admin')

@push('styles')
<style>
.page-title{font-family:'Cormorant Garamond',serif;font-size:2.2rem;color:var(--text-dark);margin-bottom:2.5rem;text-align:center}
.page-title em{font-style:italic;color:var(--brand-gold-dark)}
.form-card{background:#fff;border-radius:12px;box-shadow:0 4px 15px rgba(0,0,0,.03);border:1px solid rgba(0,0,0,.05);overflow:hidden;max-width:900px;margin:0 auto}
.form-body{padding:2.5rem}
.form-footer{background:#F8F6F2;padding:1.5rem 2.5rem;display:flex;justify-content:flex-end;border-top:1px solid rgba(0,0,0,.05)}
.info-alert{background:#FDFBF7;border:1px solid #EBE4D5;border-radius:8px;padding:1.25rem;display:flex;align-items:center;gap:.8rem;font-size:.85rem;color:#7A6953;margin-bottom:2rem}
.base-price-summary{background:#FFFFFF;border:1px solid #EBE4D5;border-radius:10px;padding:1rem 1.25rem;margin-bottom:1.25rem;display:flex;align-items:center;justify-content:space-between;gap:1rem}
.base-price-summary-label{font-size:.72rem;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.2rem}
.base-price-summary-value{font-family:'Cormorant Garamond',serif;font-size:1.8rem;font-weight:700;color:#8c7144;line-height:1}
.base-price-summary-note{font-size:.78rem;color:#7A6953;text-align:right}
.price-view-select{border:1px solid #EBE4D5;border-radius:999px;background:#FDFBF7;color:#8c7144;font-size:.75rem;font-weight:700;padding:.5rem .8rem;margin-bottom:.6rem;outline:none}
.section-title{font-family:'Cormorant Garamond',serif;font-size:1.35rem;font-weight:700;color:#8c7144;margin-bottom:1.25rem;margin-top:2rem;border-bottom:1px solid #EBEBEB;padding-bottom:.5rem}
.section-title:first-of-type{margin-top:0}
.form-label{font-size:.8rem;font-weight:600;color:#888;margin-bottom:.5rem;display:block}
.form-control,.form-select{background:#fff;border:1px solid #EBEBEB;border-radius:12px;padding:.8rem 1rem;font-size:.9rem;color:var(--text-dark);width:100%;display:block;transition:all .2s}
.form-select{appearance:none;background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23888' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");background-repeat:no-repeat;background-position:right 1rem center;background-size:12px 10px}
.form-control:focus,.form-select:focus{outline:none;border-color:var(--brand-gold);box-shadow:0 0 0 3px rgba(184,146,74,.1)}
.btn-submit{background:#A98C59;color:#fff;border:none;border-radius:50px;padding:.6rem 2.25rem;font-size:.9rem;font-weight:600;cursor:pointer;transition:background .2s}
.btn-submit:hover{background:#8A642B}
.row-form{margin-bottom:1.5rem}
.promo-table{width:100%;border-collapse:collapse;font-size:.85rem;margin-top:1rem}
.promo-table th{text-align:left;padding:.6rem 1rem;background:#F8F6F2;color:#888;font-size:.75rem;font-weight:600;text-transform:uppercase;border-bottom:1px solid #EBEBEB}
.promo-table td{padding:.75rem 1rem;border-bottom:1px solid #F5F5F5;color:var(--text-dark)}
.promo-table tr:last-child td{border-bottom:none}
.badge-active{background:#d1fae5;color:#065f46;padding:.25rem .7rem;border-radius:50px;font-size:.72rem;font-weight:600}
.badge-inactive{background:#fee2e2;color:#991b1b;padding:.25rem .7rem;border-radius:50px;font-size:.72rem;font-weight:600}
.badge-expiring{background:#fef3c7;color:#92400e;padding:.25rem .7rem;border-radius:50px;font-size:.72rem;font-weight:600}
.badge-expired{background:#fecaca;color:#7f1d1d;padding:.25rem .7rem;border-radius:50px;font-size:.72rem;font-weight:600}
.badge-upcoming{background:#bfdbfe;color:#1e40af;padding:.25rem .7rem;border-radius:50px;font-size:.72rem;font-weight:600}
.status-cell{display:flex;align-items:center;gap:.5rem}
.status-info{font-size:.8rem;color:#666}
.btn-action{padding:.3rem .6rem;border:none;border-radius:6px;font-size:.75rem;font-weight:600;cursor:pointer;transition:all .2s}
.btn-edit{background:#bfdbfe;color:#1e40af}
.btn-edit:hover{background:#93c5fd}
.btn-delete{background:#fecaca;color:#991b1b}
.btn-delete:hover{background:#fca5a5}
.seasonal-warning{display:none;background:#fff7ed;border:1px solid #fed7aa;color:#9a3412;border-radius:8px;padding:.75rem 1rem;font-size:.8rem;margin:-.5rem 0 1rem}
.cal-wrap{position:relative}
.cal-trigger{display:flex;align-items:center;justify-content:space-between;gap:.6rem;background:#fff;border:1px solid #EBEBEB;border-radius:12px;padding:.8rem 1rem;font-size:.9rem;color:var(--text-dark);width:100%;min-height:46px;cursor:pointer;transition:all .2s}
.cal-trigger:hover,.cal-trigger.open{border-color:var(--brand-gold);box-shadow:0 0 0 3px rgba(184,146,74,.1)}
.cal-trigger .cal-val{color:var(--text-dark);font-weight:500}
.cal-trigger .cal-placeholder{color:#aaa}
.cal-trigger i{color:#A98C59;font-size:.95rem}
.seasonal-cal-popup.cal-popup{position:fixed;z-index:999999;background:#fff;border:1px solid #EBE4D5;border-radius:12px;box-shadow:0 16px 40px rgba(0,0,0,.14);padding:1rem;width:320px;display:none}
.seasonal-cal-popup.cal-popup.show{display:block!important}
.cal-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem}
.cal-nav{background:none;border:1.5px solid #EBE4D5;border-radius:50%;width:28px;height:28px;display:grid;place-items:center;cursor:pointer;color:#7A6953;transition:all .2s}
.cal-nav:hover{border-color:#A98C59;color:#A98C59}
.cal-month-label{font-family:'Cormorant Garamond',serif;font-size:1rem;font-weight:700;color:#8c7144}
.cal-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:2px}
.cal-dow{text-align:center;font-size:.65rem;font-weight:700;color:#aaa;text-transform:uppercase;padding:.25rem 0}
.cal-day{text-align:center;padding:.3rem .1rem;font-size:.82rem;border-radius:6px;cursor:pointer;transition:all .15s;position:relative;line-height:1.4;color:var(--text-dark)}
.cal-day:hover:not(.cal-disabled){background:#F8F6F2;color:#A98C59}
.cal-day.cal-today{font-weight:700;color:#A98C59}
.cal-day.cal-selected{background:#A98C59!important;color:#fff!important;font-weight:700}
.cal-day.cal-disabled{color:#aaa;opacity:.35;cursor:default;pointer-events:none}
.cal-day.cal-other-month{opacity:.25;pointer-events:none}
.cal-footer{display:flex;align-items:center;justify-content:space-between;gap:.75rem;margin-top:.75rem;padding-top:.75rem;border-top:1px solid #EBE4D5}
.cal-helper{font-size:.7rem;color:#aaa}
.cal-clear{background:#F8F6F2;border:1px solid #EBE4D5;border-radius:50px;color:#7A6953;font-size:.72rem;font-weight:700;padding:.35rem .75rem;cursor:pointer}
.cal-clear:hover{border-color:#A98C59;color:#8A642B}
</style>
@endpush

@section('content')
<h1 class="page-title">Configuration <em>List</em></h1>
@php
    $today = \Carbon\Carbon::today();
    $seasonalDefaultValidFrom = old('price_valid_from', old('valid_from', $today->toDateString()));
    $seasonalDefaultValidUntil = old('valid_until');
    $activeSeasonalPeriods = ($seasonalPrices ?? collect())
        ->where('is_active', true)
        ->map(fn ($price) => [
            'id' => $price->id,
            'label' => $price->label ?: 'Villa Rate',
            'from' => optional($price->valid_from)->toDateString(),
            'until' => optional($price->valid_until)->toDateString(),
        ])
        ->values();
@endphp

@if(session('success'))
<div style="max-width:900px;margin:0 auto 1.5rem;background:#edf7ed;color:#1e4620;padding:1rem 1.25rem;border-radius:8px;font-size:.85rem;font-weight:600;display:flex;align-items:center;gap:.75rem">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
</div>
@endif

@if($errors->any())
<div style="max-width:900px;margin:0 auto 1.5rem;background:#fef2f2;color:#991b1b;padding:1rem 1.25rem;border-radius:8px;font-size:.85rem">
    <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Please fix:</strong>
    <ul style="margin:.5rem 0 0 1rem;padding:0">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<div class="form-card">
    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- BASE PRICE FORM ──────────────────────────────────────────── --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <form action="{{ route('admin.villa_settings.base_price') }}" method="POST">
        @csrf
        <div class="form-body">
            <div class="info-alert">
                <i class="bi bi-info-circle" style="font-size:1.2rem;color:var(--brand-gold-dark)"></i>
                <div>You can manage base pricing and promotional campaigns here. Be Careful!</div>
            </div>

            {{-- Base Price --}}
            <h3 class="section-title">Base Price</h3>
            <div class="base-price-summary">
                <div>
                    <div class="base-price-summary-label">{{ $priceView === 'base' ? 'Current Base Price' : 'Today Price' }}</div>
                    <div class="base-price-summary-value">
                        @if($displayPrice)
                            Rp {{ number_format($displayPrice->price_per_night, 0, ',', '.') }}
                        @else
                            Not Set
                        @endif
                    </div>
                </div>
                <div class="base-price-summary-note">
                    <select class="price-view-select" onchange="window.location.href = this.value" aria-label="Select price view">
                        <option value="{{ route('admin.villa_settings', array_merge(request()->except('price_view'), ['price_view' => 'today'])) }}" {{ $priceView === 'today' ? 'selected' : '' }}>Today Price</option>
                        <option value="{{ route('admin.villa_settings', array_merge(request()->except('price_view'), ['price_view' => 'base'])) }}" {{ $priceView === 'base' ? 'selected' : '' }}>Base Price</option>
                    </select>
                    <div>
                        @if($priceView === 'today' && $displayPrice && $displayPrice->label && $displayPrice->label !== 'Base Price')
                            Using {{ $displayPrice->label }} for {{ now()->format('d M Y') }}.
                        @elseif($priceView === 'today')
                            Using Base Price fallback for {{ now()->format('d M Y') }}.
                        @else
                            Normal-season fallback price per night.
                        @endif
                    </div>
                    @if($displayPrice && $displayPrice->updated_at)
                        <div>Updated {{ $displayPrice->updated_at->format('d M Y, H:i') }}</div>
                    @endif
                </div>
            </div>
            <div class="row row-form">
                <div class="col-12">
                    <label class="form-label">Price per Night (Rp)</label>
                    <input type="number" class="form-control" name="base_price"
                           placeholder="e.g. 4500000" value="{{ old('base_price', $basePrice?->price_per_night ? (int) $basePrice->price_per_night : '') }}" required>
                </div>
            </div>
        </div>
        <div class="form-footer">
            <button type="submit" class="btn-submit">
                <i class="bi bi-floppy me-2"></i>Save Base Price
            </button>
        </div>
    </form>
</div>

<div class="form-card" style="margin-top:2rem">
    <div class="form-body">
        <h3 class="section-title">Seasonal Prices</h3>
        <div class="info-alert" style="margin-bottom:1.5rem">
            <i class="bi bi-layers" style="font-size:1.2rem;color:var(--brand-gold-dark)"></i>
            <div>Active seasonal price periods cannot overlap. Base Price is kept as the normal-season fallback.</div>
        </div>

        <form action="{{ route('admin.villa_prices.store') }}" method="POST" id="seasonal-price-form">
            @csrf
            <div class="row row-form">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label">Label / Season Name</label>
                    <input type="text" class="form-control" name="label" placeholder="e.g. High Season" value="{{ old('label') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Price per Night (Rp)</label>
                    <input type="number" class="form-control" name="price_per_night" placeholder="e.g. 5500000" value="{{ old('price_per_night') }}" required>
                </div>
            </div>

            <div class="row row-form">
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="form-label">Valid From</label>
                    <div class="cal-wrap" id="wrap-season-from">
                        <input type="hidden" name="valid_from" id="season-valid-from" value="{{ $seasonalDefaultValidFrom }}" required>
                        <div class="cal-trigger" id="trigger-season-from" onclick="openSeasonalCal('season-from')">
                            <span id="display-season-from" class="{{ $seasonalDefaultValidFrom ? 'cal-val' : 'cal-placeholder' }}">{{ $seasonalDefaultValidFrom ? \Carbon\Carbon::parse($seasonalDefaultValidFrom)->format('d/m/Y') : 'dd/mm/yyyy' }}</span>
                            <i class="bi bi-calendar3"></i>
                        </div>
                        <div class="seasonal-cal-popup cal-popup" id="popup-season-from"></div>
                    </div>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="form-label">Valid Until</label>
                    <div class="cal-wrap" id="wrap-season-until">
                        <input type="hidden" name="valid_until" id="season-valid-until" value="{{ $seasonalDefaultValidUntil }}">
                        <div class="cal-trigger" id="trigger-season-until" onclick="openSeasonalCal('season-until')">
                            <span id="display-season-until" class="{{ $seasonalDefaultValidUntil ? 'cal-val' : 'cal-placeholder' }}">{{ $seasonalDefaultValidUntil ? \Carbon\Carbon::parse($seasonalDefaultValidUntil)->format('d/m/Y') : 'dd/mm/yyyy' }}</span>
                            <i class="bi bi-calendar3"></i>
                        </div>
                        <div class="seasonal-cal-popup cal-popup" id="popup-season-until"></div>
                    </div>
                    <small style="font-size:.72rem;color:#aaa">Leave blank to apply permanently.</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="is_active">
                        <option value="1" {{ old('is_active','1') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="seasonal-warning" id="seasonal-price-warning"></div>

            <div style="display:flex;justify-content:flex-end;margin-bottom:1.5rem">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-plus-circle me-2"></i>Save Seasonal Price
                </button>
            </div>
        </form>

        @if(($seasonalPrices ?? collect())->isEmpty())
            <p style="font-size:.85rem;color:#aaa;text-align:center;padding:1rem 0">No seasonal prices yet. Add one above.</p>
        @else
        <div style="overflow-x:auto">
            <table class="promo-table">
                <thead>
                    <tr>
                        <th>Season Name</th>
                        <th>Price per Night</th>
                        <th>Valid From</th>
                        <th>Valid Until</th>
                        <th>Status</th>
                        <th style="width:130px;text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($seasonalPrices as $price)
                        @php
                            $startDate = $price->valid_from instanceof \Carbon\Carbon
                                ? $price->valid_from->copy()->startOfDay()
                                : \Carbon\Carbon::parse($price->valid_from)->startOfDay();
                            $endDate = $price->valid_until
                                ? ($price->valid_until instanceof \Carbon\Carbon
                                    ? $price->valid_until->copy()->startOfDay()
                                    : \Carbon\Carbon::parse($price->valid_until)->startOfDay())
                                : null;
                            $isExpired = $endDate && $endDate->lt($today);
                            $isUpcoming = $startDate->gt($today);
                            $isCurrentlyActive = $price->is_active && ! $isExpired && ! $isUpcoming;
                        @endphp
                    <tr style="opacity: {{ $isExpired ? '0.65' : '1' }};">
                        <td><strong>{{ $price->label ?: 'Villa Rate' }}</strong></td>
                        <td>Rp {{ number_format($price->price_per_night, 0, ',', '.') }}</td>
                        <td style="font-size:.8rem;color:#666">
                            {{ $startDate->format('d/m/Y') }}
                        </td>
                        <td style="font-size:.8rem;color:#666">
                            {{ $endDate ? $endDate->format('d/m/Y') : 'Ongoing' }}
                        </td>
                        <td>
                            @if(! $price->is_active)
                                <span class="badge-inactive">Inactive</span>
                            @elseif($isExpired)
                                <span class="badge-expired">Expired</span>
                            @elseif($isUpcoming)
                                <span class="badge-upcoming">Upcoming</span>
                            @elseif($isCurrentlyActive)
                                <span class="badge-active">Active</span>
                            @endif
                        </td>
                        <td style="text-align:right;display:flex;gap:.5rem;justify-content:flex-end">
                            <button type="button" class="btn-action btn-edit" onclick="editVillaPrice({{ $price->id }})">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <form action="{{ route('admin.villa_prices.destroy', $price) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this seasonal price?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

<div class="form-card" style="margin-top:2rem">
    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- PROMO FORM ────────────────────────────────────────────────── --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <form action="{{ route('admin.villa_settings.save') }}" method="POST">
        @csrf
        <div class="form-body">
            {{-- Promo --}}
            <h3 class="section-title">Add / Update Promo</h3>

            <div class="row row-form">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label">Promo Name</label>
                    <input type="text" class="form-control" name="promo_name"
                           placeholder="e.g. New Year Special"
                           value="{{ old('promo_name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Promo Code <small style="color:#b8924a">(typed manually by guest)</small></label>
                    <input type="text" class="form-control" name="promo_code"
                           placeholder="e.g. NEWYEAR25"
                           value="{{ old('promo_code') }}"
                           style="text-transform:uppercase" required>
                    <small style="font-size:.72rem;color:#aaa">Otomatis huruf kapital</small>
                </div>
            </div>

            <div class="row row-form">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label">Discount (%)</label>
                    <input type="number" class="form-control" name="discount_percent"
                           placeholder="e.g. 10" min="1" max="100"
                           value="{{ old('discount_percent') }}" id="inp-discount" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Promo Status</label>
                    <select class="form-select" name="promo_status">
                        <option value="active"   {{ old('promo_status','active')==='active'   ?'selected':'' }}>Active</option>
                        <option value="inactive" {{ old('promo_status')==='inactive'?'selected':'' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="row row-form">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label">Valid From</label>
                    <input type="date" class="form-control" name="valid_from"
                           value="{{ old('valid_from') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Valid Until</label>
                    <input type="date" class="form-control" name="valid_until"
                           value="{{ old('valid_until') }}" required>
                </div>
            </div>

            <div class="p-3 mt-2" style="background:#FAFAFA;border:1px dashed #D6D6D6;border-radius:12px;display:flex;justify-content:space-between;align-items:center">
                <span style="font-size:.85rem;color:#666;font-weight:600">Estimated Final Price (per night):</span>
                <div id="finalPriceDisplay" style="font-size:1.5rem;font-weight:700;color:var(--brand-gold-dark)">Rp 0</div>
            </div>

            {{-- Daftar promo --}}
            <h3 class="section-title">Active Promos</h3>

            @if(($promos ?? collect())->isEmpty())
                <p style="font-size:.85rem;color:#aaa;text-align:center;padding:1rem 0">No promos yet. Add one above.</p>
            @else
            <div style="overflow-x:auto">
                <table class="promo-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Discount</th>
                            <th>Valid Period</th>
                            <th>Status & Validity</th>
                            <th style="width:120px;text-align:right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($promos as $p)
                            @php
                                $startDate = \Carbon\Carbon::parse($p->valid_from)->startOfDay();
                                $endDate = \Carbon\Carbon::parse($p->valid_until)->startOfDay();
                                $daysLeft = max(0, (int) $today->diffInDays($endDate, false));
                                $daysUntilStart = max(0, (int) $today->diffInDays($startDate, false));
                                $daysEnded = max(0, (int) $endDate->diffInDays($today, false));
                                $isExpired = $endDate->lt($today);
                                $isNotStarted = $startDate->gt($today);
                                $isActive = !$isExpired && !$isNotStarted && $p->is_active;
                                $expiringSoon = $isActive && $daysLeft >= 1 && $daysLeft <= 7;
                            @endphp
                        <tr style="opacity: {{ $isExpired ? '0.6' : '1' }};">
                            <td><strong>{{ $p->code }}</strong></td>
                            <td>{{ $p->name }}</td>
                            <td><span style="background:#f0f0f0;padding:.2rem .5rem;border-radius:4px;font-weight:600">{{ $p->discount_percent }}%</span></td>
                            <td style="font-size:.8rem;color:#666">
                                {{ $startDate->format('d M Y') }} → {{ $endDate->format('d M Y') }}
                                <br/>
                                @if($isNotStarted)
                                    <span style="color:#f59e0b">Starts in {{ $daysUntilStart }} days</span>
                                @endif
                            </td>
                            <td>
                                <div class="status-cell">
                                    @if($isExpired)
                                        <span class="badge-expired">Expired</span>
                                        <span class="status-info">(ended {{ $daysEnded }} days ago)</span>
                                    @elseif($isNotStarted)
                                        <span style="background:#bfdbfe;color:#1e40af;padding:.25rem .7rem;border-radius:50px;font-size:.72rem;font-weight:600">Pending</span>
                                        <span class="status-info">(starts in {{ $daysUntilStart }} days)</span>
                                    @elseif(!$p->is_active)
                                        <span class="badge-inactive">Inactive</span>
                                    @elseif($expiringSoon)
                                        <span class="badge-expiring">⚠️ Expiring Soon</span>
                                        <span class="status-info">({{ $daysLeft }} days left)</span>
                                    @else
                                        <span class="badge-active">✓ Active</span>
                                        <span class="status-info">({{ $daysLeft }} days left)</span>
                                    @endif
                                </div>
                            </td>
                            <td style="text-align:right;display:flex;gap:.5rem;justify-content:flex-end">
                                <button type="button" class="btn-action btn-edit" onclick="editPromo({{ $p->id }})">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <form action="{{ route('admin.promo.destroy', $p->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus promo {{ $p->code }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

        </div>
        <div class="form-footer">
            <button type="submit" class="btn-submit">
                <i class="bi bi-floppy me-2"></i>Save Promo
            </button>
        </div>
    </form>
</div>

<script>
const TODAY_FROM_CARBON = @json($today->toDateString());
const SEASONAL_PRICE_PERIODS = @json($activeSeasonalPeriods);
const INFINITY_DATE = '9999-12-31';
const SEASONAL_OVERLAP_MESSAGE = 'Seasonal price period overlaps with an existing active seasonal price.';
const SEASONAL_CAL_FIELDS = {
    'season-from': {
        inputId: 'season-valid-from',
        displayId: 'display-season-from',
        triggerId: 'trigger-season-from',
        popupId: 'popup-season-from',
        formId: 'seasonal-price-form',
        warningId: 'seasonal-price-warning',
        allowClear: false,
        isUntil: false,
        untilKey: 'season-until',
        ignoreId: null,
    },
    'season-until': {
        inputId: 'season-valid-until',
        displayId: 'display-season-until',
        triggerId: 'trigger-season-until',
        popupId: 'popup-season-until',
        formId: 'seasonal-price-form',
        warningId: 'seasonal-price-warning',
        allowClear: true,
        isUntil: true,
        fromKey: 'season-from',
        ignoreId: null,
    },
    'edit-season-from': {
        inputId: 'edit-price-valid-from',
        displayId: 'display-edit-season-from',
        triggerId: 'trigger-edit-season-from',
        popupId: 'popup-edit-season-from',
        formId: 'editVillaPriceForm',
        warningId: 'edit-seasonal-price-warning',
        allowClear: false,
        isUntil: false,
        untilKey: 'edit-season-until',
        ignoreId: null,
    },
    'edit-season-until': {
        inputId: 'edit-price-valid-until',
        displayId: 'display-edit-season-until',
        triggerId: 'trigger-edit-season-until',
        popupId: 'popup-edit-season-until',
        formId: 'editVillaPriceForm',
        warningId: 'edit-seasonal-price-warning',
        allowClear: true,
        isUntil: true,
        fromKey: 'edit-season-from',
        ignoreId: null,
    },
};
const seasonalCalState = {
    activeKey: null,
    viewMonth: {},
};

document.addEventListener("DOMContentLoaded", function () {
    const baseInput     = document.querySelector('input[name="base_price"]');
    const discInput     = document.getElementById('inp-discount');
    const display       = document.getElementById('finalPriceDisplay');
    const codeInput     = document.querySelector('input[name="promo_code"]');

    codeInput.addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });

    function calc() {
        let base = parseFloat(baseInput.value) || 0;
        let disc = parseFloat(discInput.value)  || 0;
        if (disc > 100) disc = 100;
        if (disc < 0)   disc = 0;
        display.textContent = new Intl.NumberFormat('id-ID', {
            style:'currency', currency:'IDR', maximumFractionDigits:0
        }).format(base - base * disc / 100);
    }

    baseInput.addEventListener('input', calc);
    discInput.addEventListener('input',  calc);

    initSeasonalCalendars();
    bindSeasonalValidation();
});

function initSeasonalCalendars() {
    Object.keys(SEASONAL_CAL_FIELDS).forEach((key) => {
        const config = SEASONAL_CAL_FIELDS[key];
        const input = document.getElementById(config.inputId);

        if (!input) {
            return;
        }

        setSeasonalDate(key, input.value, false);
        seasonalCalState.viewMonth[key] = monthFromDate(input.value || TODAY_FROM_CARBON);

        input.addEventListener('change', () => updateSeasonalWarning(config.formId, config.ignoreId, config.warningId));
    });
}

function openSeasonalCal(key) {
    const config = SEASONAL_CAL_FIELDS[key];
    const popup = document.getElementById(config.popupId);
    const trigger = document.getElementById(config.triggerId);

    if (!popup || !trigger) {
        return;
    }

    closeSeasonalCals();
    seasonalCalState.activeKey = key;
    seasonalCalState.viewMonth[key] = seasonalCalState.viewMonth[key] || monthFromDate(getSeasonalValue(key) || TODAY_FROM_CARBON);
    renderSeasonalCal(key);
    popup.classList.add('show');
    trigger.classList.add('open');
    positionSeasonalCal(key);
}

function closeSeasonalCals() {
    Object.values(SEASONAL_CAL_FIELDS).forEach((config) => {
        document.getElementById(config.popupId)?.classList.remove('show');
        document.getElementById(config.triggerId)?.classList.remove('open');
    });
    seasonalCalState.activeKey = null;
}

function positionSeasonalCal(key) {
    const config = SEASONAL_CAL_FIELDS[key];
    const trigger = document.getElementById(config.triggerId);
    const popup = document.getElementById(config.popupId);

    if (!trigger || !popup) {
        return;
    }

    const rect = trigger.getBoundingClientRect();
    const margin = 12;
    let left = rect.left;
    let top = rect.bottom + 8;

    if (left + popup.offsetWidth > window.innerWidth - margin) {
        left = window.innerWidth - popup.offsetWidth - margin;
    }

    if (top + popup.offsetHeight > window.innerHeight - margin) {
        top = Math.max(margin, rect.top - popup.offsetHeight - 8);
    }

    popup.style.left = `${Math.max(margin, left)}px`;
    popup.style.top = `${top}px`;
}

function renderSeasonalCal(key) {
    const config = SEASONAL_CAL_FIELDS[key];
    const popup = document.getElementById(config.popupId);
    const view = seasonalCalState.viewMonth[key] || monthFromDate(TODAY_FROM_CARBON);

    if (!popup) {
        return;
    }

    popup.innerHTML = '';

    const header = document.createElement('div');
    header.className = 'cal-header';

    const prev = document.createElement('button');
    prev.type = 'button';
    prev.className = 'cal-nav';
    prev.innerHTML = '<i class="bi bi-chevron-left"></i>';
    prev.addEventListener('click', (event) => {
        event.stopPropagation();
        seasonalCalState.viewMonth[key] = addMonths(view, -1);
        renderSeasonalCal(key);
    });

    const next = document.createElement('button');
    next.type = 'button';
    next.className = 'cal-nav';
    next.innerHTML = '<i class="bi bi-chevron-right"></i>';
    next.addEventListener('click', (event) => {
        event.stopPropagation();
        seasonalCalState.viewMonth[key] = addMonths(view, 1);
        renderSeasonalCal(key);
    });

    const label = document.createElement('div');
    label.className = 'cal-month-label';
    label.textContent = new Date(view.year, view.month - 1, 1).toLocaleDateString('en-US', {
        month: 'long',
        year: 'numeric',
    });

    header.appendChild(prev);
    header.appendChild(label);
    header.appendChild(next);
    popup.appendChild(header);

    const grid = document.createElement('div');
    grid.className = 'cal-grid';

    ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].forEach((day) => {
        const el = document.createElement('div');
        el.className = 'cal-dow';
        el.textContent = day;
        grid.appendChild(el);
    });

    const firstDay = new Date(view.year, view.month - 1, 1).getDay();
    const daysInMonth = new Date(view.year, view.month, 0).getDate();

    for (let i = 0; i < firstDay; i++) {
        const empty = document.createElement('div');
        empty.className = 'cal-day cal-other-month';
        grid.appendChild(empty);
    }

    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${view.year}-${pad2(view.month)}-${pad2(day)}`;
        const btn = document.createElement('div');
        btn.className = 'cal-day';
        btn.textContent = day;

        if (dateStr === TODAY_FROM_CARBON) {
            btn.classList.add('cal-today');
        }

        if (dateStr === getSeasonalValue(key)) {
            btn.classList.add('cal-selected');
        }

        if (isSeasonalDateDisabled(key, dateStr)) {
            btn.classList.add('cal-disabled');
        } else {
            btn.addEventListener('click', () => pickSeasonalDate(key, dateStr));
        }

        grid.appendChild(btn);
    }

    popup.appendChild(grid);

    const footer = document.createElement('div');
    footer.className = 'cal-footer';

    const helper = document.createElement('div');
    helper.className = 'cal-helper';
    helper.textContent = config.allowClear ? 'Leave blank to apply permanently.' : 'Other active periods cannot be selected.';
    footer.appendChild(helper);

    if (config.allowClear) {
        const clear = document.createElement('button');
        clear.type = 'button';
        clear.className = 'cal-clear';
        clear.textContent = 'Clear';
        clear.addEventListener('click', (event) => {
            event.stopPropagation();
            clearSeasonalDate(key);
        });
        footer.appendChild(clear);
    }

    popup.appendChild(footer);
}

function pickSeasonalDate(key, dateStr) {
    const config = SEASONAL_CAL_FIELDS[key];

    setSeasonalDate(key, dateStr, true);

    if (!config.isUntil && config.untilKey) {
        const untilValue = getSeasonalValue(config.untilKey);
        if (untilValue && untilValue < dateStr) {
            setSeasonalDate(config.untilKey, '', true);
        }
    }

    closeSeasonalCals();
    updateSeasonalWarning(config.formId, config.ignoreId, config.warningId);
}

function clearSeasonalDate(key) {
    const config = SEASONAL_CAL_FIELDS[key];
    setSeasonalDate(key, '', true);
    closeSeasonalCals();
    updateSeasonalWarning(config.formId, config.ignoreId, config.warningId);
}

function setSeasonalDate(key, value, shouldNotify = true) {
    const config = SEASONAL_CAL_FIELDS[key];
    const input = document.getElementById(config.inputId);
    const display = document.getElementById(config.displayId);

    if (!input || !display) {
        return;
    }

    input.value = formatDateInput(value);
    display.textContent = input.value ? formatDateDisplay(input.value) : 'dd/mm/yyyy';
    display.className = input.value ? 'cal-val' : 'cal-placeholder';
    seasonalCalState.viewMonth[key] = monthFromDate(input.value || TODAY_FROM_CARBON);

    if (shouldNotify) {
        input.dispatchEvent(new Event('change', { bubbles: true }));
    }
}

function getSeasonalValue(key) {
    const config = SEASONAL_CAL_FIELDS[key];
    return document.getElementById(config.inputId)?.value || '';
}

function isSeasonalDateDisabled(key, dateStr) {
    const config = SEASONAL_CAL_FIELDS[key];
    const minDate = config.isUntil && config.fromKey
        ? (getSeasonalValue(config.fromKey) || TODAY_FROM_CARBON)
        : TODAY_FROM_CARBON;

    if (dateStr < minDate) {
        return true;
    }

    if (!isSeasonalCalendarActive(key)) {
        return false;
    }

    return SEASONAL_PRICE_PERIODS
        .filter((period) => String(period.id) !== String(config.ignoreId))
        .some((period) => {
            if (!period.from) {
                return false;
            }

            return dateStr >= period.from && dateStr <= (period.until || INFINITY_DATE);
        });
}

function isSeasonalCalendarActive(key) {
    const config = SEASONAL_CAL_FIELDS[key];
    const form = document.getElementById(config.formId);
    const status = form?.querySelector('[name="is_active"]');

    return !status || status.value === '1';
}

function monthFromDate(value) {
    const date = formatDateInput(value) || TODAY_FROM_CARBON;
    const [year, month] = date.split('-').map(Number);

    return { year, month };
}

function addMonths(view, amount) {
    const date = new Date(view.year, view.month - 1 + amount, 1);

    return {
        year: date.getFullYear(),
        month: date.getMonth() + 1,
    };
}

function pad2(value) {
    return String(value).padStart(2, '0');
}

function formatDateDisplay(value) {
    const date = formatDateInput(value);

    if (!date) {
        return 'dd/mm/yyyy';
    }

    const [year, month, day] = date.split('-');
    return `${day}/${month}/${year}`;
}

function bindSeasonalValidation() {
    const createForm = document.getElementById('seasonal-price-form');
    const editForm = document.getElementById('editVillaPriceForm');
    const createStatus = createForm?.querySelector('[name="is_active"]');
    const editStatus = editForm?.querySelector('[name="is_active"]');

    if (createForm) {
        createForm.addEventListener('submit', function (event) {
            if (!validateSeasonalForm(createForm, null, 'seasonal-price-warning')) {
                event.preventDefault();
            }
        });
    }

    if (createStatus) {
        createStatus.addEventListener('change', () => {
            updateSeasonalWarning('seasonal-price-form', null, 'seasonal-price-warning');
            refreshOpenSeasonalCal();
        });
    }

    if (editForm) {
        editForm.addEventListener('submit', function (event) {
            const priceId = document.getElementById('edit-villa-price-id').value;
            if (!validateSeasonalForm(editForm, priceId, 'edit-seasonal-price-warning')) {
                event.preventDefault();
            }
        });
    }

    if (editStatus) {
        editStatus.addEventListener('change', () => {
            const priceId = document.getElementById('edit-villa-price-id').value;
            updateSeasonalWarning('editVillaPriceForm', priceId, 'edit-seasonal-price-warning');
            refreshOpenSeasonalCal();
        });
    }
}

function refreshOpenSeasonalCal() {
    if (seasonalCalState.activeKey) {
        renderSeasonalCal(seasonalCalState.activeKey);
        positionSeasonalCal(seasonalCalState.activeKey);
    }
}

function validateSeasonalForm(form, ignoreId, warningId) {
    const from = form.querySelector('[name="valid_from"]').value;
    const until = form.querySelector('[name="valid_until"]').value;
    const isActive = form.querySelector('[name="is_active"]').value === '1';
    const warning = document.getElementById(warningId);

    hideSeasonalWarning(warning);

    if (until && from && until < from) {
        showSeasonalWarning(warning, 'Valid until cannot be earlier than valid from.');
        return false;
    }

    if (isActive && hasSeasonalOverlap(from, until, ignoreId)) {
        showSeasonalWarning(warning, SEASONAL_OVERLAP_MESSAGE);
        return false;
    }

    return true;
}

function updateSeasonalWarning(formId, ignoreId, warningId) {
    const form = document.getElementById(formId);

    if (form) {
        validateSeasonalForm(form, ignoreId, warningId);
    }
}

function hasSeasonalOverlap(from, until, ignoreId = null) {
    if (!from) {
        return false;
    }

    const newEnd = until || INFINITY_DATE;

    return SEASONAL_PRICE_PERIODS
        .filter((period) => String(period.id) !== String(ignoreId))
        .some((period) => {
            if (!period.from) {
                return false;
            }

            const existingEnd = period.until || INFINITY_DATE;
            return from <= existingEnd && newEnd >= period.from;
        });
}

function showSeasonalWarning(element, message) {
    if (!element) {
        alert(message);
        return;
    }

    element.textContent = message;
    element.style.display = 'block';
}

function hideSeasonalWarning(element) {
    if (!element) {
        return;
    }

    element.textContent = '';
    element.style.display = 'none';
}

async function editPromo(id) {
    try {
        const response = await fetch(`/admin/promo/${id}/edit`);
        const promo = await response.json();

        // Populate form
        document.getElementById('edit-promo-id').value = promo.id;
        document.getElementById('edit-promo-name').value = promo.name;
        document.getElementById('edit-discount').value = promo.discount_percent;
        document.getElementById('edit-valid-from').value = formatDateInput(promo.valid_from);
        document.getElementById('edit-valid-until').value = formatDateInput(promo.valid_until);
        document.getElementById('edit-promo-status').value = promo.is_active ? 'active' : 'inactive';

        // Show modal
        document.getElementById('editPromoModal').style.display = 'flex';
    } catch (error) {
        alert('Error loading promo');
        console.error(error);
    }
}

function formatDateInput(value) {
    if (!value) {
        return '';
    }

    if (/^\d{4}-\d{2}-\d{2}$/.test(value)) {
        return value;
    }

    return String(value).slice(0, 10);
}

function closeEditModal() {
    document.getElementById('editPromoModal').style.display = 'none';
}

async function editVillaPrice(id) {
    try {
        const response = await fetch(`/admin/villa-prices/${id}/edit`);
        const price = await response.json();

        document.getElementById('edit-villa-price-id').value = price.id;
        document.getElementById('editVillaPriceForm').action = `/admin/villa-prices/${price.id}`;
        document.getElementById('edit-price-label').value = price.label || '';
        document.getElementById('edit-price-per-night').value = Math.round(Number(price.price_per_night || 0));
        SEASONAL_CAL_FIELDS['edit-season-from'].ignoreId = price.id;
        SEASONAL_CAL_FIELDS['edit-season-until'].ignoreId = price.id;
        setSeasonalDate('edit-season-from', price.valid_from, true);
        setSeasonalDate('edit-season-until', price.valid_until, true);
        document.getElementById('edit-price-status').value = price.is_active ? '1' : '0';
        hideSeasonalWarning(document.getElementById('edit-seasonal-price-warning'));
        document.getElementById('editVillaPriceModal').style.display = 'flex';
    } catch (error) {
        alert('Error loading seasonal price');
        console.error(error);
    }
}

function closeVillaPriceModal() {
    closeSeasonalCals();
    document.getElementById('editVillaPriceModal').style.display = 'none';
}

document.addEventListener('click', function(event) {
    if (!event.target.closest('.cal-wrap') && !event.target.closest('.cal-popup')) {
        closeSeasonalCals();
    }
});

document.addEventListener('scroll', closeSeasonalCals, true);
window.addEventListener('resize', closeSeasonalCals);

window.onclick = function(event) {
    const promoModal = document.getElementById('editPromoModal');
    const priceModal = document.getElementById('editVillaPriceModal');
    if (event.target === promoModal) {
        promoModal.style.display = 'none';
    }
    if (event.target === priceModal) {
        closeSeasonalCals();
        priceModal.style.display = 'none';
    }
}
</script>

<!-- Edit Seasonal Price Modal -->
<div id="editVillaPriceModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:12px;padding:2rem;max-width:520px;width:90%;box-shadow:0 10px 40px rgba(0,0,0,.2)">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
            <h3 style="font-family:'Cormorant Garamond',serif;font-size:1.5rem;color:#8c7144;margin:0">Edit Seasonal Price</h3>
            <button onclick="closeVillaPriceModal()" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:#999">&times;</button>
        </div>

        <form id="editVillaPriceForm" method="POST" style="display:flex;flex-direction:column;gap:1rem">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit-villa-price-id">

            <div>
                <label style="display:block;font-size:.8rem;font-weight:600;color:#888;margin-bottom:.5rem">Label / Season Name</label>
                <input type="text" id="edit-price-label" name="label" style="width:100%;padding:.8rem 1rem;border:1px solid #EBEBEB;border-radius:8px;font-size:.9rem">
            </div>

            <div>
                <label style="display:block;font-size:.8rem;font-weight:600;color:#888;margin-bottom:.5rem">Price per Night (Rp)</label>
                <input type="number" id="edit-price-per-night" name="price_per_night" min="0" style="width:100%;padding:.8rem 1rem;border:1px solid #EBEBEB;border-radius:8px;font-size:.9rem" required>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:600;color:#888;margin-bottom:.5rem">Valid From</label>
                    <div class="cal-wrap" id="wrap-edit-season-from">
                        <input type="hidden" id="edit-price-valid-from" name="valid_from" required>
                        <div class="cal-trigger" id="trigger-edit-season-from" onclick="openSeasonalCal('edit-season-from')">
                            <span id="display-edit-season-from" class="cal-placeholder">dd/mm/yyyy</span>
                            <i class="bi bi-calendar3"></i>
                        </div>
                        <div class="seasonal-cal-popup cal-popup" id="popup-edit-season-from"></div>
                    </div>
                </div>
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:600;color:#888;margin-bottom:.5rem">Valid Until</label>
                    <div class="cal-wrap" id="wrap-edit-season-until">
                        <input type="hidden" id="edit-price-valid-until" name="valid_until">
                        <div class="cal-trigger" id="trigger-edit-season-until" onclick="openSeasonalCal('edit-season-until')">
                            <span id="display-edit-season-until" class="cal-placeholder">dd/mm/yyyy</span>
                            <i class="bi bi-calendar3"></i>
                        </div>
                        <div class="seasonal-cal-popup cal-popup" id="popup-edit-season-until"></div>
                    </div>
                    <small style="font-size:.72rem;color:#aaa">Leave blank to apply permanently.</small>
                </div>
            </div>

            <div>
                <label style="display:block;font-size:.8rem;font-weight:600;color:#888;margin-bottom:.5rem">Status</label>
                <select id="edit-price-status" name="is_active" style="width:100%;padding:.8rem 1rem;border:1px solid #EBEBEB;border-radius:8px;font-size:.9rem" required>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div class="seasonal-warning" id="edit-seasonal-price-warning"></div>

            <div style="display:flex;gap:1rem;margin-top:1rem">
                <button type="submit" style="flex:1;background:#A98C59;color:#fff;border:none;border-radius:8px;padding:.8rem;font-weight:600;cursor:pointer">Save Changes</button>
                <button type="button" onclick="closeVillaPriceModal()" style="flex:1;background:#f0f0f0;color:#666;border:none;border-radius:8px;padding:.8rem;font-weight:600;cursor:pointer">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Promo Modal -->
<div id="editPromoModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:12px;padding:2rem;max-width:500px;width:90%;box-shadow:0 10px 40px rgba(0,0,0,.2)">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
            <h3 style="font-family:'Cormorant Garamond',serif;font-size:1.5rem;color:#8c7144;margin:0">Edit Promo</h3>
            <button onclick="closeEditModal()" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:#999">&times;</button>
        </div>
        
        <form id="editPromoForm" method="POST" style="display:flex;flex-direction:column;gap:1rem">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit-promo-id">
            
            <div>
                <label style="display:block;font-size:.8rem;font-weight:600;color:#888;margin-bottom:.5rem">Promo Name</label>
                <input type="text" id="edit-promo-name" name="promo_name" style="width:100%;padding:.8rem 1rem;border:1px solid #EBEBEB;border-radius:8px;font-size:.9rem" required>
            </div>
            
            <div>
                <label style="display:block;font-size:.8rem;font-weight:600;color:#888;margin-bottom:.5rem">Discount (%)</label>
                <input type="number" id="edit-discount" name="discount_percent" min="1" max="100" style="width:100%;padding:.8rem 1rem;border:1px solid #EBEBEB;border-radius:8px;font-size:.9rem" required>
            </div>
            
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:600;color:#888;margin-bottom:.5rem">Valid From</label>
                    <input type="date" id="edit-valid-from" name="valid_from" style="width:100%;padding:.8rem 1rem;border:1px solid #EBEBEB;border-radius:8px;font-size:.9rem" required>
                </div>
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:600;color:#888;margin-bottom:.5rem">Valid Until</label>
                    <input type="date" id="edit-valid-until" name="valid_until" style="width:100%;padding:.8rem 1rem;border:1px solid #EBEBEB;border-radius:8px;font-size:.9rem" required>
                </div>
            </div>
            
            <div>
                <label style="display:block;font-size:.8rem;font-weight:600;color:#888;margin-bottom:.5rem">Status</label>
                <select id="edit-promo-status" name="promo_status" style="width:100%;padding:.8rem 1rem;border:1px solid #EBEBEB;border-radius:8px;font-size:.9rem" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            
            <div style="display:flex;gap:1rem;margin-top:1rem">
                <button type="submit" style="flex:1;background:#A98C59;color:#fff;border:none;border-radius:8px;padding:.8rem;font-weight:600;cursor:pointer">Save Changes</button>
                <button type="button" onclick="closeEditModal()" style="flex:1;background:#f0f0f0;color:#666;border:none;border-radius:8px;padding:.8rem;font-weight:600;cursor:pointer">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('editPromoForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const promoId = document.getElementById('edit-promo-id').value;
    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('_token', document.querySelector('input[name="_token"]').value);
    formData.append('promo_name', document.getElementById('edit-promo-name').value);
    formData.append('discount_percent', document.getElementById('edit-discount').value);
    formData.append('valid_from', document.getElementById('edit-valid-from').value);
    formData.append('valid_until', document.getElementById('edit-valid-until').value);
    formData.append('promo_status', document.getElementById('edit-promo-status').value);
    
    fetch(`/admin/promo/${promoId}`, {
        method: 'POST',
        body: formData
    }).then(response => {
        if (response.ok) {
            window.location.reload();
        } else {
            alert('Error updating promo');
        }
    }).catch(error => {
        alert('Error: ' + error);
    });
});

</script>
@endsection
