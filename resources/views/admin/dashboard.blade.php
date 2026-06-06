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
        display: flex;
        gap: 1.5rem;
        margin-bottom: 3rem;
        justify-content: center;
    }
    
    .metric-card {
        background-color: #FFFFFF;
        border-radius: 12px;
        padding: 1.5rem;
        flex: 1;
        min-width: 140px;
        max-width: 200px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
        align-items: center;
        border: 1px solid rgba(0,0,0,0.05);
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
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        .metric-card {
            flex: 1 1 calc(50% - 0.75rem);
            min-width: 0;
            max-width: none;
            padding: 1.25rem 1rem;
        }
        .metric-card:last-child {
            flex: 1 1 100%;
        }
        .metric-value {
            font-size: 2rem;
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
            <div class="metric-value">Rp {{ number_format($revenue, 0, ',', '.') }}</div>
        </div>
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
