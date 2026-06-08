@extends('layouts.admin')

@push('styles')
<style>
    .page-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.2rem;
        color: var(--text-dark);
        margin-bottom: 2.5rem;
        text-align: center;
    }
    .page-title em {
        font-style: italic;
        color: var(--brand-gold-dark);
    }

    /* Metric Cards */
    .metric-cards-container {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
        width: 100%;
    }
    
    .metric-card {
        background-color: #FFFFFF;
        border-radius: 12px;
        padding: 1.5rem;
        min-width: 0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
        align-items: center;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .price-summary-card {
        background: linear-gradient(135deg, #FFFDF8 0%, #F7EEDC 100%);
        border: 1px solid rgba(166, 124, 55, 0.35);
        box-shadow: 0 12px 28px rgba(166, 124, 55, 0.14);
        border-radius: 12px;
        padding: 1.35rem 1.5rem;
        margin: 0 0 3rem;
        width: 100%;
        position: relative;
        overflow: hidden;
        display: grid;
        grid-template-columns: auto minmax(180px, 1fr) auto;
        align-items: center;
        gap: 1.25rem;
    }

    .price-summary-card::before {
        content: "";
        position: absolute;
        inset: 0 0 auto 0;
        height: 4px;
        background: linear-gradient(90deg, #A67C37, #D6B56D);
    }

    .price-summary-icon {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(166, 124, 55, 0.12);
        color: #8A642B;
        font-size: 1.35rem;
    }

    .price-summary-label {
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #7A6953;
        margin-bottom: 0.25rem;
    }

    .price-summary-value {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2rem;
        font-weight: 700;
        color: #8A642B;
        line-height: 1.05;
    }

    .price-summary-note {
        color: #7A6953;
        font-size: 0.78rem;
        font-weight: 700;
        margin-top: 0.35rem;
    }

    .metric-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        width: 100%;
    }

    .metric-icon-box {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .icon-brown { background-color: #F6F3E9; color: #A67C37; }
    .icon-red { background-color: #FDE8E8; color: #E02424; }
    .icon-green { background-color: #Edf7ed; color: #1E4620; }
    /* The mockup pending looks orange/salmon, we'll use a soft red tint */

    .metric-title {
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        line-height: 1.2;
    }

    .metric-value {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--brand-gold-dark);
    }

    .metric-value.currency {
        font-size: 1.45rem;
        line-height: 1.15;
        text-align: center;
        overflow-wrap: anywhere;
    }

    .metric-filter-select {
        width: 100%;
        border: 1px solid #EBE4D5;
        border-radius: 999px;
        background: #FDFBF7;
        color: var(--brand-gold-dark);
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.45rem 0.75rem;
        margin-bottom: 1rem;
        outline: none;
    }

    .price-summary-card .metric-filter-select {
        width: 190px;
        margin-bottom: 0;
        background: #FFFFFF;
    }

    .metric-note {
        margin-top: 0.6rem;
        color: var(--text-muted);
        font-size: 0.72rem;
        text-align: center;
        line-height: 1.3;
    }

    /* Grids */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }

    .widget-panel {
        background-color: #FFFFFF;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.05);
    }

    .widget-title {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 1.5rem;
    }

    /* Table styling */
    .table-transactions {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }

    .table-transactions th {
        font-size: 0.7rem;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        padding-bottom: 1rem;
        border-bottom: 1px solid #EBEBEB;
    }

    .table-transactions td {
        padding: 1.25rem 0;
        border-bottom: 1px solid #F5F5F5;
        color: var(--text-dark);
    }
    
    .table-transactions tr:last-child td {
        border-bottom: none;
        padding-bottom: 0;
    }

    .tx-code { font-weight: 600; }
    .tx-guest { font-weight: 600; color: #555; }
    .tx-date { color: var(--text-muted); }
    .tx-status-confirmed { color: #888; font-weight: 600; font-size: 0.75rem; letter-spacing: 0.05em;}

    .widget-pagination-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid #F0F0F0;
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .widget-pagination {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .widget-page-item {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 1px solid #EBEBEB;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #333;
        text-decoration: none;
        font-weight: 600;
        transition: background-color 0.2s, color 0.2s;
    }

    .widget-page-item:hover {
        background-color: #F5F5F5;
    }

    .widget-page-item.active {
        color: var(--brand-gold-dark);
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .widget-page-item.disabled {
        color: #A0A0A0;
        cursor: not-allowed;
        background-color: #FAFAFA;
    }

    /* Calendar Mockup */
    .calendar-mock {
        width: 100%;
    }
    .calendar-month {
        text-align: center;
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--brand-gold-dark);
        margin-bottom: 1.5rem;
    }
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        text-align: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: var(--text-muted);
    }
    .calendar-header {
        font-size: 0.65rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    .calendar-legend {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        margin-top: 2rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-muted);
    }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .dot-occupied {
        width: 10px; height: 10px; border-radius: 50%;
        background: linear-gradient(135deg, #DFCAA5 0%, #D4BC97 100%);
        border: 1px solid #C9A97A;
    }
    .dot-today {
        width: 10px; height: 10px; border-radius: 50%;
        background: #fff;
        border: 1.5px solid #b8924a;
    }

    @media (max-width: 991px) {
        .page-title {
            font-size: 1.6rem;
            margin-bottom: 1.5rem;
        }
        .metric-cards-container {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.75rem;
        }
        .metric-card {
            min-width: 0;
            padding: 1.25rem 1rem;
        }
        .metric-card:last-child {
            grid-column: 1 / -1;
        }
        .metric-value {
            font-size: 2rem;
        }
        .metric-value.currency {
            font-size: 1.25rem;
        }
        .price-summary-card {
            margin: 0 0 1.5rem;
            grid-template-columns: auto 1fr;
            gap: 1rem;
        }
        .price-summary-card .metric-filter-select {
            grid-column: 1 / -1;
            width: 100%;
        }
        .price-summary-value {
            font-size: 1.6rem;
        }
        .dashboard-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        .widget-panel {
            padding: 1.25rem;
            max-width: 100vw;
            overflow-x: hidden;
        }
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .table-transactions {
            min-width: 600px;
        }
        .widget-pagination-footer {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .widget-pagination {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
    <h1 class="page-title">Swarna Mandapa's <em>Summary</em></h1>

    <!-- Metrics -->
    <div class="metric-cards-container">
        <!-- 1 -->
        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-icon-box icon-brown"><i class="bi bi-door-closed"></i></div>
                <div class="metric-title">Total<br>Booking</div>
            </div>
            <div class="metric-value">{{ $totalBookings }}</div>
        </div>
        <!-- 2 -->
        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-icon-box" style="background:#FFF3F3; color:#E05A5A;"><i class="bi bi-clock"></i></div>
                <div class="metric-title">Pending</div>
            </div>
            <div class="metric-value">{{ $pendingBookings }}</div>
        </div>
        <!-- 3 -->
        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-icon-box icon-green"><i class="bi bi-check2"></i></div>
                <div class="metric-title">Confirmed</div>
            </div>
            <div class="metric-value">{{ $confirmedBookings }}</div>
        </div>
        <!-- 4 -->
        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-icon-box icon-red"><i class="bi bi-x"></i></div>
                <div class="metric-title">Declined</div>
            </div>
            <div class="metric-value">{{ $cancelledBookings }}</div>
        </div>
        <!-- 5 -->
        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-icon-box icon-green" style="color: #4CAF50; background: #E8F5E9;"><i class="bi bi-currency-dollar"></i></div>
                <div class="metric-title">Revenue</div>
            </div>
            <div class="metric-value currency">Rp {{ number_format($revenue, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="price-summary-card">
        <div class="price-summary-icon"><i class="bi bi-house-heart"></i></div>
        <div>
            <div class="price-summary-label">{{ $priceView === 'base' ? 'Base Price' : 'Today Price' }}</div>
            <div class="price-summary-value">
                @if($displayPrice)
                    Rp {{ number_format($displayPrice->price_per_night, 0, ',', '.') }}
                @else
                    -
                @endif
            </div>
            <div class="price-summary-note">
                @if($priceView === 'today' && $displayPrice && $displayPrice->label && $displayPrice->label !== 'Base Price')
                    {{ $displayPrice->label }}
                @elseif($priceView === 'today')
                    Base Price fallback
                @else
                    Normal-season fallback
                @endif
            </div>
        </div>
        <select class="metric-filter-select" onchange="window.location.href = this.value" aria-label="Select price view">
            <option value="{{ route('admin.dashboard', array_merge(request()->except('price_view'), ['price_view' => 'today'])) }}" {{ $priceView === 'today' ? 'selected' : '' }}>Today Price</option>
            <option value="{{ route('admin.dashboard', array_merge(request()->except('price_view'), ['price_view' => 'base'])) }}" {{ $priceView === 'base' ? 'selected' : '' }}>Base Price</option>
        </select>
    </div>

    <!-- Main Grid -->
    <div class="dashboard-grid">
        <!-- Transactions -->
        <div class="widget-panel">
            <div class="widget-title">Recent Transaction</div>
            <div class="table-responsive">
                <table class="table-transactions">
                    <thead>
                    <tr>
                        <th class="text-start">CODE TRX</th>
                        <th class="text-start">GUEST</th>
                        <th class="text-start">DATE</th>
                        <th class="text-end">PAYMENT STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @if($recentBookings->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center" style="padding:2rem; color:#666;">No bookings available yet.</td>
                        </tr>
                    @else
                        @foreach($recentBookings as $booking)
                            <tr>
                                <td class="tx-code">{{ $booking->booking_code }}</td>
                                <td class="tx-guest">{{ $booking->first_name }} {{ $booking->last_name }}</td>
                                <td class="tx-date">{{ $booking->check_in->format('M d') }} - {{ $booking->check_out->format('M d, Y') }}</td>
                                <td class="text-end tx-status-confirmed">{{ $booking->status }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            </div>
            <div class="widget-pagination-footer">
                <div>
                    Showing {{ $recentBookings->firstItem() ?? 0 }}-{{ $recentBookings->lastItem() ?? 0 }} of {{ $recentBookings->total() }} entries
                </div>
                <div class="widget-pagination">
                    @if($recentBookings->onFirstPage())
                        <span class="widget-page-item disabled"><i class="bi bi-chevron-left" style="font-size:0.7rem"></i></span>
                    @else
                        <a href="{{ $recentBookings->previousPageUrl() }}" class="widget-page-item" aria-label="Previous transaction page"><i class="bi bi-chevron-left" style="font-size:0.7rem"></i></a>
                    @endif

                    @php
                        $transactionStartPage = max(1, $recentBookings->currentPage() - 1);
                        $transactionEndPage = min($recentBookings->lastPage(), $recentBookings->currentPage() + 1);
                    @endphp

                    @for($page = $transactionStartPage; $page <= $transactionEndPage; $page++)
                        <a href="{{ $recentBookings->url($page) }}" class="widget-page-item {{ $recentBookings->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
                    @endfor

                    @if($recentBookings->hasMorePages())
                        <a href="{{ $recentBookings->nextPageUrl() }}" class="widget-page-item" aria-label="Next transaction page"><i class="bi bi-chevron-right" style="font-size:0.7rem"></i></a>
                    @else
                        <span class="widget-page-item disabled"><i class="bi bi-chevron-right" style="font-size:0.7rem"></i></span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Availability -->
        <div class="widget-panel">
            <div class="widget-title">Availability</div>
            
            <div class="calendar-mock">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem">
                    <button id="dashCalPrev" onclick="dashCalNav(-1)" style="background:none;border:1.5px solid #ddd;border-radius:50%;width:28px;height:28px;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;color:#999;transition:all .2s" onmouseenter="this.style.borderColor='#b8924a';this.style.color='#b8924a'" onmouseleave="this.style.borderColor='#ddd';this.style.color='#999'">&#8249;</button>
                    <div class="calendar-month" id="dashboardCalendarTitle">Loading...</div>
                    <button id="dashCalNext" onclick="dashCalNav(1)" style="background:none;border:1.5px solid #ddd;border-radius:50%;width:28px;height:28px;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;color:#999;transition:all .2s" onmouseenter="this.style.borderColor='#b8924a';this.style.color='#b8924a'" onmouseleave="this.style.borderColor='#ddd';this.style.color='#999'">&#8250;</button>
                </div>

                <div class="calendar-grid calendar-header">
                    <div>SUN</div><div>MON</div><div>TUE</div><div>WED</div><div>THU</div><div>FRI</div><div>SAT</div>
                </div>
                
                <div class="calendar-grid" id="dashboardCalendarGrid"></div>

                <div class="calendar-legend">
                    <div class="legend-item"><div class="dot-occupied"></div> OCCUPIED</div>
                    <div class="legend-item"><div class="dot-today"></div> TODAY</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const DASH_MONTHS = ["January","February","March","April","May","June","July","August","September","October","November","December"];
        const dashToday = new Date();
        
        let dashYear  = dashToday.getFullYear();
        let dashMonth = dashToday.getMonth(); // 0-indexed

        async function renderDashCal() {
            const title = document.getElementById('dashboardCalendarTitle');
            const grid  = document.getElementById('dashboardCalendarGrid');
            
            title.textContent = `${DASH_MONTHS[dashMonth]} ${dashYear}`;
            grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:1rem;font-size:.8rem;color:#aaa">Loading...</div>';

            try {
                const resp = await fetch(`/api/calendar-data?year=${dashYear}&month=${dashMonth + 1}`);
                const data = await resp.json();
                const booked = data.booked || {};

                const firstDay  = new Date(dashYear, dashMonth, 1).getDay();
                const totalDays = new Date(dashYear, dashMonth + 1, 0).getDate();
                let html = '';

                // Padding awal
                for (let i = 0; i < firstDay; i++) {
                    html += '<div class="calendar-day" style="visibility:hidden"></div>';
                }

                for (let day = 1; day <= totalDays; day++) {
                    const dateStr  = `${dashYear}-${String(dashMonth + 1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
                    const isBooked = booked[dateStr] !== undefined;
                    const isTodayDay = dashYear === dashToday.getFullYear()
                                    && dashMonth === dashToday.getMonth()
                                    && day === dashToday.getDate();

                    let style = '';
                    let inner = day;

                    if (isBooked) {
                        style = 'background:linear-gradient(135deg,#DFCAA5 0%,#D4BC97 100%);color:#55442A;';
                    }

                    if (isTodayDay) {
                        inner = `<div style="position:relative;display:inline-flex;flex-direction:column;align-items:center;width:100%">
                            ${day}
                            <span style="position:absolute;bottom:-5px;left:50%;transform:translateX(-50%);width:5px;height:5px;background:#fff;border-radius:50%"></span>
                        </div>`;
                    }

                    html += `<div class="calendar-day" style="${style}">${inner}</div>`;
                }

                grid.innerHTML = html;
            } catch(e) {
                grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:1rem;font-size:.8rem;color:#e05a5a">Failed to load</div>';
            }
        }

        function dashCalNav(dir) {
            dashMonth += dir;
            if (dashMonth > 11) { dashMonth = 0;  dashYear++; }
            if (dashMonth < 0)  { dashMonth = 11; dashYear--; }
            renderDashCal();
        }

        document.addEventListener('DOMContentLoaded', renderDashCal);
    </script>
@endsection
