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

    .legend-box.available {
        background-color: #FFFFFF;
        border-color: #F0F0F0;
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
                    const isRealToday = (realToday.getFullYear() === year && realToday.getMonth() === month && realToday.getDate() === day);

                    let classes = "day-box";
                    let title = "";

                    if (isBooked) {
                        classes += " booked";
                        title = `Booked (${isBooked.status})`;
                    }

                    // Cute little gold dot indicator for "Today"
                    let indicator = isRealToday ? '<div style="position:absolute; top:0.5rem; left:0.5rem; width:8px; height:8px; background-color:var(--brand-gold-dark); border-radius:50%; box-shadow: 0 0 5px rgba(0,0,0,0.1);" title="Today"></div>' : "";

                    htmlContent += `
                        <div class="${classes}" title="${title}">
                            <span class="day-number">${day}</span>
                            ${indicator}
                        </div>
                    `;
                }

                gridContainer.innerHTML = htmlContent;
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
    </div>
@endsection
