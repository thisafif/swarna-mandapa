@extends('layouts.admin')

@push('styles')
<style>
    .page-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.2rem;
        color: var(--text-dark);
        margin-bottom: 3rem;
        text-align: center;
    }
    .page-title em {
        font-style: italic;
        color: var(--brand-gold-dark);
    }

    .calendar-container {
        background-color: #FFFFFF;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.05);
        overflow: hidden;
        padding: 2.5rem;
        max-width: 1000px;
        margin: 0 auto;
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        border-bottom: 1px solid #EBEBEB;
        padding-bottom: 1.5rem;
    }

    .calendar-month-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #8c7144; /* Matched the slightly darker beige/gold in mockup */
        margin: 0;
    }

    .calendar-controls {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }

    .btn-cal-nav {
        background: none;
        border: none;
        color: #888;
        font-size: 1.25rem; /* Larger font size to match mockup */
        cursor: pointer;
        padding: 0;
        transition: color 0.2s;
        display: flex;
        align-items: center;
    }
    .btn-cal-nav:hover {
        color: var(--brand-gold-dark);
    }

    .btn-today {
        background: #FFFFFF;
        border: 1px solid #EAEAEA;
        border-radius: 50px;
        padding: 0.4rem 1.5rem;
        font-size: 0.8rem;
        font-weight: 700;
        color: #395777; /* Subtle greyish-blue text color from the mockup */
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.2s;
        letter-spacing: 0.05em;
    }
    .btn-today:hover {
        border-color: #C0C0C0;
        color: var(--text-dark);
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1.25rem;
    }

    .calendar-weekday {
        text-align: center;
        font-size: 0.8rem;
        font-weight: 700;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding-bottom: 0.5rem;
    }

    .day-box {
        aspect-ratio: 1; /* Automatically forms perfect squares */
        border: 1px solid #F0F0F0;
        border-radius: 12px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s, box-shadow 0.2s;
        background-color: #FFFFFF;
        cursor: pointer;
    }

    .day-box:hover {
        border-color: #D6D6D6;
    }

    .day-number {
        font-size: 1.15rem;
        font-weight: 700;
        color: #7A6953;
        z-index: 2;
    }

    /* ─── Status: Booked (dari booking PENDING/CONFIRMED) ─── */
    .day-box.booked {
        background: linear-gradient(135deg, #DFCAA5 0%, #D4BC97 100%);
        border-color: #DFCAA5;
        box-shadow: inset 0 0 10px rgba(0,0,0,0.05);
    }
    .day-box.booked .day-number {
        color: #55442A;
    }

    /* ─── Status: Booked (dari booking PENDING/CONFIRMED) ─── */
    .calendar-legend {
        display: flex;
        gap: 2rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #EBEBEB;
        flex-wrap: wrap;
        justify-content: center;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.85rem;
        color: #666;
    }

    .legend-box {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }

    .legend-box.booked {
        background: linear-gradient(135deg, #DFCAA5 0%, #D4BC97 100%);
        border-color: #DFCAA5;
    }

    .legend-box.blocked {
        background-color: #FFE8E8;
        border-color: #F5A5A5;
    }

    .day-box.blocked {
        background: #E5E7EB;
        border-color: #CBD5E1;
    }
    .day-box.blocked .day-number {
        color: #4B5563;
    }

    .legend-box.available {
        background-color: #FFFFFF;
        border-color: #F0F0F0;
    }

    .detail-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        z-index: 1040;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s;
        display: none;
    }
    .detail-backdrop.show {
        display: block;
        opacity: 1;
        pointer-events: auto;
    }
    .detail-panel {
        position: fixed;
        top: 0;
        right: -440px;
        width: 100%;
        max-width: 440px;
        height: 100vh;
        background: #FFFFFF;
        z-index: 1050;
        box-shadow: -5px 0 25px rgba(0,0,0,0.08);
        transition: right 0.3s ease;
        display: flex;
        flex-direction: column;
        visibility: hidden;
    }
    .detail-panel.show {
        right: 0;
        visibility: visible;
    }
    .dp-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #EBEBEB;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #FAFAFA;
    }
    .dp-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--brand-gold-dark);
        margin: 0;
    }
    .dp-close {
        background: none;
        border: none;
        font-size: 1.25rem;
        color: #888;
        cursor: pointer;
        padding: 0.5rem;
    }
    .dp-body {
        padding: 2rem;
        overflow-y: auto;
        flex-grow: 1;
    }
    .dp-section {
        margin-bottom: 2rem;
    }
    .dp-label {
        font-size: 0.7rem;
        font-weight: 700;
        color: #777;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.75rem;
    }
    .dp-value {
        font-size: 0.95rem;
        color: #222;
        font-weight: 500;
        line-height: 1.5;
    }
    .dp-value-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }
    .dp-status {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: #EDF7ED;
        color: #1E4620;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.05em;
    }
    .dp-status.pending {
        background: #FFF8E5;
        color: #7A5A00;
    }
    .dp-status.blocked {
        background: #F3F4F6;
        color: #374151;
    }
    .dp-status.available {
        background: #EDF7ED;
        color: #1E4620;
    }
    .dp-card {
        background: #FDFBF7;
        border: 1px solid #EBE4D5;
        border-radius: 8px;
        padding: 1rem;
    }
    .dp-row {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 0.6rem;
        color: #555;
        font-size: 0.9rem;
    }
    .dp-row:last-child {
        margin-bottom: 0;
    }
    .dp-row strong {
        color: #222;
    }
    .dp-divider {
        border: 0;
        border-top: 1px solid #EBEBEB;
        margin: 1.5rem 0;
    }

    @media (max-width: 991px) {
        .calendar-grid {
            gap: 0.35rem;
        }
        .day-box {
            border-radius: 8px;
        }
        .day-number {
            font-size: 1rem;
        }
        .calendar-weekday {
            font-size: 0.65rem;
            padding-bottom: 0.25rem;
        }
    }
    
    @media (max-width: 768px) {
        .page-title {
            font-size: 1.8rem;
        }
        .calendar-container {
            padding: 1.5rem 0.75rem;
        }
        .calendar-month-title {
            font-size: 1.25rem;
        }
        .btn-today {
            padding: 0.35rem 0.75rem;
        }
        .detail-panel {
            max-width: 100vw;
            right: -100vw;
        }
        .dp-header,
        .dp-body {
            padding-left: 1.25rem;
            padding-right: 1.25rem;
        }
        /* Mobile: show 3 letters of weekday to fit properly without wrapping */
        .weekday-full { display: none; }
        .weekday-short { display: inline; }
    }
    
    @media (min-width: 769px) {
        .weekday-full { display: inline; }
        .weekday-short { display: none; }
    }
</style>
@endpush

@section('content')
    <h1 class="page-title">Availability <em>Calendar</em></h1>

    <div class="calendar-container">
        <!-- Header -->
        <div class="calendar-header">
            <h2 class="calendar-month-title" id="monthYearDisplay">February 2026</h2>
            <div class="calendar-controls">
                <button class="btn-cal-nav" id="btnPrevMonth"><i class="bi bi-chevron-left"></i></button>
                <button class="btn-today" id="btnToday">TODAY</button>
                <button class="btn-cal-nav" id="btnNextMonth"><i class="bi bi-chevron-right"></i></button>
            </div>
        </div>

        <!-- Grid -->
        <div class="calendar-grid" id="calendarGrid">
            <!-- Weekdays Header -->
            @foreach(['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $day)
                <div class="calendar-weekday">
                    <span class="weekday-full">{{ strtoupper($day) }}</span>
                    <span class="weekday-short">{{ substr(strtoupper($day), 0, 3) }}</span>
                </div>
            @endforeach

            <!-- Days container populated by JS -->
        </div>
    </div>

    <!-- Detail Backdrop & Panel (Offcanvas) -->
    <div class="detail-backdrop" id="detailBackdrop" onclick="closeCalendarDetail()"></div>
    <div class="detail-panel" id="detailPanel">
        <div class="dp-header">
            <h3 class="dp-title" id="detailPanelTitle">Date Detail</h3>
            <button class="dp-close" type="button" onclick="closeCalendarDetail()" aria-label="Close detail panel"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="dp-body" id="detailPanelBody"></div>
    </div>

    <!-- Calendar JS Interactivity -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            
            let calendarData = {
                booked: {},
                blocked: {}
            };

            const gridContainer = document.getElementById('calendarGrid');
            const monthDisplay = document.getElementById('monthYearDisplay');
            const detailBackdrop = document.getElementById('detailBackdrop');
            const detailPanel = document.getElementById('detailPanel');
            const detailPanelTitle = document.getElementById('detailPanelTitle');
            const detailPanelBody = document.getElementById('detailPanelBody');

            // Start dari bulan sekarang
            let currentDate = new Date(); 

            // Simpan weekdays HTML
            const weekdaysHTML = `
                @foreach(['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $day)
                    <div class="calendar-weekday">
                        <span class="weekday-full">{{ strtoupper($day) }}</span>
                        <span class="weekday-short">{{ substr(strtoupper($day), 0, 3) }}</span>
                    </div>
                @endforeach
            `;

            /**
             * Fetch calendar data dari backend API
             */
            async function fetchCalendarData(year, month) {
                try {
                    const response = await fetch(`/api/calendar-data?year=${year}&month=${month}`);
                    const data = await response.json();
                    calendarData = data;
                } catch (error) {
                    console.error('Error fetching calendar data:', error);
                }
            }

            function escapeHtml(value) {
                return String(value ?? '').replace(/[&<>"']/g, function(char) {
                    return {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;'
                    }[char];
                });
            }

            function formatDate(dateStr) {
                if (!dateStr) return '-';
                return new Date(dateStr + 'T00:00:00').toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            }

            function formatRupiah(value) {
                return 'Rp ' + Math.round(Number(value || 0)).toLocaleString('id-ID');
            }

            function calculateNights(checkIn, checkOut) {
                const start = new Date(checkIn + 'T00:00:00');
                const end = new Date(checkOut + 'T00:00:00');
                return Math.max(0, Math.round((end - start) / (1000 * 60 * 60 * 24)));
            }

            function getNightlyBreakdown(bookingData) {
                if (!bookingData.nightly_price_breakdown) return [];
                if (Array.isArray(bookingData.nightly_price_breakdown)) return bookingData.nightly_price_breakdown;
                try {
                    return JSON.parse(bookingData.nightly_price_breakdown);
                } catch (e) {
                    return [];
                }
            }

            function renderNightlyBreakdown(bookingData) {
                const breakdown = getNightlyBreakdown(bookingData);
                if (!breakdown.length) return '';

                const rows = breakdown.map(item => `
                    <div class="dp-row">
                        <span>${escapeHtml(formatDate(item.date))} ${item.label ? `(${escapeHtml(item.label)})` : ''}</span>
                        <strong>${formatRupiah(item.price)}</strong>
                    </div>
                `).join('');

                return `
                    <div class="dp-section">
                        <div class="dp-label">Nightly Breakdown</div>
                        <div class="dp-card">${rows}</div>
                    </div>
                `;
            }

            function renderBookedDetail(dateStr, bookingData) {
                const nights = calculateNights(bookingData.check_in, bookingData.check_out);
                const breakdown = getNightlyBreakdown(bookingData);
                const subtotal = breakdown.length
                    ? breakdown.reduce((sum, item) => sum + Number(item.price || 0), 0)
                    : Number(bookingData.price_per_night || 0) * nights;
                const statusClass = String(bookingData.status || '').toLowerCase();
                const fullName = `${bookingData.first_name || ''} ${bookingData.last_name || ''}`.trim() || 'Guest';

                detailPanelTitle.textContent = 'Booking Detail';
                detailPanelBody.innerHTML = `
                    <div class="dp-section">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="dp-label mb-0">Booking Ref</div>
                            <div class="dp-status ${escapeHtml(statusClass)}">${escapeHtml(bookingData.status || 'BOOKED')}</div>
                        </div>
                        <div class="dp-value" style="font-size:1.2rem; color:var(--brand-gold-dark); font-weight:700;">${escapeHtml(bookingData.booking_code || '-')}</div>
                        <div style="font-size:0.85rem; color:#666; margin-top:0.4rem;">Selected date: ${escapeHtml(formatDate(dateStr))}</div>
                    </div>

                    <hr class="dp-divider">

                    <div class="dp-section">
                        <div class="dp-label">Guest Information</div>
                        <div class="dp-value">
                            <strong style="font-size:1.05rem;">${escapeHtml(fullName)}</strong><br>
                            <span style="color:#666; font-size:0.85rem; display:inline-block; margin-top:0.4rem;">
                                <i class="bi bi-whatsapp" style="margin-right:0.3rem"></i> ${escapeHtml(bookingData.phone || '-')}<br>
                                <i class="bi bi-envelope" style="margin-right:0.3rem"></i> ${escapeHtml(bookingData.email || '-')}
                            </span>
                        </div>
                    </div>

                    <div class="dp-section">
                        <div class="dp-label">Stay Details</div>
                        <div class="dp-value-group mb-3">
                            <div>
                                <div style="font-size:0.75rem; color:#888; font-weight:600; margin-bottom:0.2rem;">Check-in</div>
                                <div style="font-weight:700; color:#222;">${escapeHtml(formatDate(bookingData.check_in))}</div>
                            </div>
                            <div>
                                <div style="font-size:0.75rem; color:#888; font-weight:600; margin-bottom:0.2rem;">Check-out</div>
                                <div style="font-weight:700; color:#222;">${escapeHtml(formatDate(bookingData.check_out))}</div>
                            </div>
                        </div>
                        <div class="dp-card">
                            <div class="dp-row"><span>Nights</span><strong>${nights}</strong></div>
                            <div class="dp-row"><span>Guests</span><strong>${escapeHtml(bookingData.guests || 0)} Guest(s)</strong></div>
                            <div class="dp-row"><span>Source</span><strong>${bookingData.is_manual ? 'Manual booking' : 'Online booking'}</strong></div>
                        </div>
                    </div>

                    ${String(bookingData.notes || '').trim() ? `
                        <div class="dp-section">
                            <div class="dp-label">Notes</div>
                            <div class="dp-card" style="color:#555; line-height:1.6; white-space:pre-line;">${escapeHtml(String(bookingData.notes || '').trim())}</div>
                        </div>
                    ` : ''}

                    <hr class="dp-divider">

                    <div class="dp-section">
                        <div class="dp-label">Payment Summary</div>
                        <div class="dp-row"><span>Average Price/Night</span><strong>${formatRupiah(bookingData.price_per_night)}</strong></div>
                        <div class="dp-row"><span>Subtotal</span><strong>${formatRupiah(subtotal)}</strong></div>
                        <div class="dp-row"><span>Discount</span><strong>${formatRupiah(bookingData.discount_amount)}</strong></div>
                        <div class="dp-row"><span>Promo</span><strong>${escapeHtml(bookingData.promo_code || '-')}</strong></div>
                        <div class="dp-row" style="border-top:1px dashed #DDD; padding-top:0.75rem; margin-top:0.75rem;">
                            <span style="color:#222; font-weight:600;">Total Amount</span>
                            <strong style="font-size:1.05rem; color:var(--brand-gold-dark);">${formatRupiah(bookingData.total_price)}</strong>
                        </div>
                    </div>

                    ${renderNightlyBreakdown(bookingData)}
                `;
            }

            function renderBlockedDetail(dateStr, blockedData) {
                detailPanelTitle.textContent = 'Blocked Date';
                detailPanelBody.innerHTML = `
                    <div class="dp-section">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="dp-label mb-0">Date</div>
                            <div class="dp-status blocked">${escapeHtml(blockedData.status || 'BLOCKED')}</div>
                        </div>
                        <div class="dp-value" style="font-size:1.2rem; color:var(--brand-gold-dark); font-weight:700;">${escapeHtml(formatDate(dateStr))}</div>
                    </div>

                    <hr class="dp-divider">

                    <div class="dp-section">
                        <div class="dp-label">Block Detail</div>
                        <div class="dp-card">
                            <div class="dp-row"><span>Type</span><strong>${escapeHtml(blockedData.type || 'blocked')}</strong></div>
                            <div class="dp-row"><span>Status</span><strong>${escapeHtml(blockedData.status || 'BLOCKED')}</strong></div>
                            <div class="dp-row"><span>Reason</span><strong>${escapeHtml(blockedData.reason || '-')}</strong></div>
                        </div>
                    </div>

                    <div class="dp-section">
                        <div class="dp-value" style="color:#666;">This date is marked unavailable and cannot be booked.</div>
                    </div>
                `;
            }

            function renderAvailableDetail(dateStr) {
                detailPanelTitle.textContent = 'Available Date';
                detailPanelBody.innerHTML = `
                    <div class="dp-section">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="dp-label mb-0">Date</div>
                            <div class="dp-status available">AVAILABLE</div>
                        </div>
                        <div class="dp-value" style="font-size:1.2rem; color:var(--brand-gold-dark); font-weight:700;">${escapeHtml(formatDate(dateStr))}</div>
                    </div>

                    <hr class="dp-divider">

                    <div class="dp-section">
                        <div class="dp-label">Availability</div>
                        <div class="dp-card">
                            <div class="dp-row"><span>Status</span><strong>Available</strong></div>
                        </div>
                    </div>

                    <div class="dp-section">
                        <div class="dp-value" style="color:#666;">There is no booking or block recorded for this date.</div>
                    </div>
                `;
            }

            function openCalendarDetail(dateStr) {
                const bookingData = calendarData.booked && calendarData.booked[dateStr];
                const blockedData = calendarData.blocked && calendarData.blocked[dateStr];

                if (bookingData) {
                    renderBookedDetail(dateStr, bookingData);
                } else if (blockedData) {
                    renderBlockedDetail(dateStr, blockedData);
                } else {
                    renderAvailableDetail(dateStr);
                }

                detailBackdrop.classList.add('show');
                detailPanel.classList.add('show');
            }

            window.closeCalendarDetail = function() {
                detailBackdrop.classList.remove('show');
                detailPanel.classList.remove('show');
            };

            function attachDayClickHandlers() {
                gridContainer.querySelectorAll('.day-box[data-date]').forEach(dayBox => {
                    dayBox.addEventListener('click', () => openCalendarDetail(dayBox.dataset.date));
                });
            }

            function renderCalendar(dateObj) {
                const year = dateObj.getFullYear();
                const month = dateObj.getMonth(); // 0-11

                // 1. Update Title
                monthDisplay.textContent = monthNames[month] + " " + year;

                // 2. Compute calendar grid offsets
                const firstDayIndex = new Date(year, month, 1).getDay(); // 0(Sun) - 6(Sat)
                const totalDays = new Date(year, month + 1, 0).getDate(); // Days in current month

                let htmlContent = weekdaysHTML;

                // Add empty padding boxes
                for (let i = 0; i < firstDayIndex; i++) {
                    htmlContent += '<div class="day-box" style="visibility: hidden; border: none; background: transparent;"></div>';
                }

                // Render actual days
                const realToday = new Date();
                for (let day = 1; day <= totalDays; day++) {
                    const mStr = String(month + 1).padStart(2, '0');
                    const dStr = String(day).padStart(2, '0');
                    const dateStr = `${year}-${mStr}-${dStr}`;

                    // Check status dari data
                    const isBooked = calendarData.booked && calendarData.booked[dateStr];
                    const isBlocked = calendarData.blocked && calendarData.blocked[dateStr];
                    const isRealToday = (realToday.getFullYear() === year && realToday.getMonth() === month && realToday.getDate() === day);

                    let classes = "day-box";
                    let title = "";

                    if (isBooked) {
                        classes += " booked";
                        title = `Booked (${isBooked.status})`;
                    } else if (isBlocked) {
                        classes += " blocked";
                        title = isBlocked.reason ? `Blocked (${isBlocked.reason})` : `Blocked (${isBlocked.status})`;
                    } else {
                        title = "Available";
                    }

                    // Cute little gold dot indicator for "Today"
                    let indicator = isRealToday ? '<div style="position:absolute; top:0.5rem; left:0.5rem; width:8px; height:8px; background-color:var(--brand-gold-dark); border-radius:50%; box-shadow: 0 0 5px rgba(0,0,0,0.1);" title="Today"></div>' : "";

                    htmlContent += `
                        <div class="${classes}" title="${escapeHtml(title)}" data-date="${dateStr}">
                            <span class="day-number">${day}</span>
                            ${indicator}
                        </div>
                    `;
                }

                gridContainer.innerHTML = htmlContent;
                attachDayClickHandlers();
            }

            /**
             * Initialize dan fetch data
             */
            async function initCalendar() {
                await fetchCalendarData(currentDate.getFullYear(), currentDate.getMonth() + 1);
                renderCalendar(currentDate);
            }

            // Initial load
            initCalendar();

            // Button Navigations
            document.getElementById('btnPrevMonth').addEventListener('click', async () => {
                currentDate.setMonth(currentDate.getMonth() - 1);
                await fetchCalendarData(currentDate.getFullYear(), currentDate.getMonth() + 1);
                renderCalendar(currentDate);
            });
            
            document.getElementById('btnNextMonth').addEventListener('click', async () => {
                currentDate.setMonth(currentDate.getMonth() + 1);
                await fetchCalendarData(currentDate.getFullYear(), currentDate.getMonth() + 1);
                renderCalendar(currentDate);
            });
            
            document.getElementById('btnToday').addEventListener('click', async () => {
                currentDate = new Date();
                await fetchCalendarData(currentDate.getFullYear(), currentDate.getMonth() + 1);
                renderCalendar(currentDate);
            });
        });
    </script>

    <!-- Legend -->
    <div class="calendar-legend">
        <div class="legend-item">
            <div class="legend-box available"></div>
            <span>Available</span>
        </div>
        <div class="legend-item">
            <div class="legend-box booked"></div>
            <span>📅 Booked (Reservation)</span>
        </div>
        <div class="legend-item">
            <div class="legend-box blocked"></div>
            <span>Blocked / Maintenance</span>
        </div>
    </div>
@endsection
