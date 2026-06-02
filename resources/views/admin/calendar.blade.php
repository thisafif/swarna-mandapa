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
        padding: 1rem;
        transition: transform 0.2s, box-shadow 0.2s;
        background-color: #FFFFFF;
    }

    .day-box:hover {
        border-color: #D6D6D6;
    }

    .day-number {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 1.15rem;
        font-weight: 700;
        color: #7A6953;
    }

    .day-box.occupied {
        background-color: #DFCAA5; /* Accurate gold-sand fill matching mockup */
        border-color: #DFCAA5;
    }
    .day-box.occupied .day-number {
        color: #55442A; /* High contrast text for gold background */
    }

    /* Custom Pop-up Tooltip */
    .custom-tooltip {
        position: absolute;
        bottom: calc(100% + 5px);
        left: 50%;
        transform: translateX(-50%) translateY(10px);
        background: #FFFFFF;
        border: 1px solid #EAEAEA;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        padding: 1rem;
        border-radius: 10px;
        min-width: 220px;
        z-index: 100;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        pointer-events: none;
        text-align: left;
    }

    .custom-tooltip::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border-width: 8px;
        border-style: solid;
        border-color: #FFFFFF transparent transparent transparent;
    }

    .custom-tooltip::before {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border-width: 9px;
        border-style: solid;
        border-color: #EAEAEA transparent transparent transparent;
        z-index: -1;
    }

    .day-box.occupied:hover .custom-tooltip {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(0);
    }

    @media (max-width: 991px) {
        .calendar-grid {
            gap: 0.5rem;
        }
        .day-box {
            border-radius: 8px;
            padding: 0.5rem;
        }
        .day-number {
            font-size: 0.95rem;
            top: 0.5rem;
            right: 0.5rem;
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
            padding: 1.5rem 1rem;
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
            
            // Fetch real data from controller
            const occupiedData = {!! $occupiedDatesJson ?? '{}' !!};

            const gridContainer = document.getElementById('calendarGrid');
            const monthDisplay = document.getElementById('monthYearDisplay');

            // Set current date based on today
            let currentDate = new Date(); 

            // Save the weekdays HTML so we don't accidentally wipe it out
            const weekdaysHTML = `
                @foreach(['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $day)
                    <div class="calendar-weekday">
                        <span class="weekday-full">{{ strtoupper($day) }}</span>
                        <span class="weekday-short">{{ substr(strtoupper($day), 0, 3) }}</span>
                    </div>
                @endforeach
            `;

            function renderCalendar(dateObj) {
                const year = dateObj.getFullYear();
                const month = dateObj.getMonth(); // 0-11

                // 1. Update Title
                monthDisplay.textContent = monthNames[month] + " " + year;

                // 2. Compute calendar grid offsets
                const firstDayIndex = new Date(year, month, 1).getDay(); // 0(Sun) - 6(Sat)
                const totalDays = new Date(year, month + 1, 0).getDate(); // Days in current month

                let htmlContent = weekdaysHTML;

                // Add empty padding boxes for days of the week before the 1st
                for (let i = 0; i < firstDayIndex; i++) {
                    htmlContent += '<div class="day-box" style="visibility: hidden; border: none; background: transparent;"></div>';
                }

                // Render actual days
                const realToday = new Date();
                for (let day = 1; day <= totalDays; day++) {
                    // Stringify to compare with dummy format (e.g. "2026-02-26")
                    const mStr = String(month + 1).padStart(2, '0');
                    const dStr = String(day).padStart(2, '0');
                    const dateStr = `${year}-${mStr}-${dStr}`;

                    const isOccupied = occupiedData.hasOwnProperty(dateStr);
                    const isRealToday = (realToday.getFullYear() === year && realToday.getMonth() === month && realToday.getDate() === day);

                    let classes = "day-box";
                    if (isOccupied) classes += " occupied";
                    
                    // Cute little gold dot indicator for "Today"
                    let indicator = isRealToday ? '<div style="position:absolute; bottom:0.5rem; left:0.5rem; width:8px; height:8px; background-color:var(--brand-gold-dark); border-radius:50%; box-shadow: 0 0 5px rgba(0,0,0,0.1);" title="Today"></div>' : "";

                    let guestInfo = "";
                    let hoverStyle = "";
                    
                    if (isOccupied) {
                        const data = occupiedData[dateStr];
                        hoverStyle = `style="cursor:pointer;"`;
                        
                        // Badge styling based on status
                        let badgeBg = data.status === 'CONFIRMED' ? '#Edf7ed' : '#FFF3F3';
                        let badgeColor = data.status === 'CONFIRMED' ? '#1E4620' : '#E05A5A';

                        guestInfo = `
                            <div class="custom-tooltip">
                                <div style="font-size:0.65rem; font-weight:700; color:#888; text-transform:uppercase; margin-bottom:0.4rem; letter-spacing:0.05em;">Booking Detail</div>
                                <div style="font-weight:700; color:#222; font-size:0.95rem; line-height:1.2; word-wrap:break-word;">${data.guest}</div>
                                <div style="margin-top:0.6rem;">
                                    <div style="font-size:0.75rem; color:#666; font-family:monospace; margin-bottom:0.4rem;"><i class="bi bi-tag-fill text-gold me-1"></i> ${data.code}</div>
                                    <span style="display:inline-block; padding:0.2rem 0.6rem; background:${badgeBg}; color:${badgeColor}; border-radius:50px; font-size:0.65rem; font-weight:700; letter-spacing:0.05em;">${data.status}</span>
                                </div>
                            </div>
                        `;
                    }

                    htmlContent += `
                        <div class="${classes}" ${hoverStyle}>
                            <span class="day-number">${day}</span>
                            ${guestInfo}
                            ${indicator}
                        </div>
                    `;
                }

                gridContainer.innerHTML = htmlContent;
            }

            // Initial render
            renderCalendar(currentDate);

            // Button Navigations
            document.getElementById('btnPrevMonth').addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar(currentDate);
            });
            
            document.getElementById('btnNextMonth').addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar(currentDate);
            });
            
            document.getElementById('btnToday').addEventListener('click', () => {
                currentDate = new Date(); // Reset to real-world today
                renderCalendar(currentDate);
            });
        });
    </script>
@endsection
