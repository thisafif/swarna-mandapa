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

    .form-card {
        background-color: #FFFFFF;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.05);
        overflow: hidden;
        max-width: 900px;
        margin: 0 auto;
    }

    .form-body {
        padding: 2.5rem;
    }

    .form-footer {
        background-color: #F8F6F2;
        padding: 1.5rem 2.5rem;
        display: flex;
        justify-content: flex-end;
        border-top: 1px solid rgba(0,0,0,0.05);
    }

    .info-alert {
        background-color: #FDFBF7; /* Very light cream/gold tint */
        border: 1px solid #EBE4D5;
        border-radius: 8px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.8rem;
        color: #7A6953;
        margin-bottom: 2rem;
    }

    .info-alert i {
        font-size: 1.2rem;
        color: var(--brand-gold-dark);
    }

    .form-label {
        font-size: 0.8rem;
        font-weight: 500;
        color: #666;
        margin-bottom: 0.5rem;
    }

    .form-control {
        background-color: #FFFFFF;
        border: 1px solid #EBEBEB;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        color: var(--text-dark);
        box-shadow: 0 2px 5px rgba(0,0,0,0.01);
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--brand-gold);
        box-shadow: 0 0 0 3px rgba(184, 146, 74, 0.1);
    }

    .btn-submit {
        background-color: var(--brand-gold-dark);
        color: #FFFFFF;
        border: none;
        border-radius: 8px;
        padding: 0.85rem 1.5rem;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-submit:hover {
        background-color: #8A642B;
    }

    /* Floating row gaps */
    .row-form {
        margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 1.8rem;
            margin-bottom: 2rem;
        }
        .form-body {
            padding: 1.5rem;
        }
        .form-footer {
            padding: 1.5rem;
            justify-content: stretch;
        }
        .btn-submit {
            width: 100%;
        }
        .info-alert {
            align-items: flex-start;
        }
    }

    /* Modal Styling */
    .success-modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2000;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s;
    }
    .success-modal-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }
    .success-modal-content {
        background: #fff;
        border-radius: 12px;
        padding: 2.5rem;
        max-width: 400px;
        width: 90%;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transform: translateY(20px);
        transition: transform 0.3s;
    }
    .success-modal-overlay.active .success-modal-content {
        transform: translateY(0);
    }
    .success-icon {
        width: 60px;
        height: 60px;
        background: #F0FDF4; /* light green */
        color: #16A34A; /* dark green */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        margin: 0 auto 1.5rem;
    }
    .success-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1.2rem;
    }
    .reservation-details {
        background: #F8F6F2;
        border: 1px dashed #D6D6D6;
        border-radius: 8px;
        padding: 1.25rem;
        font-size: 0.85rem;
        color: #666;
        margin-bottom: 2rem;
        text-align: left;
    }
    .reservation-details strong {
        color: var(--text-dark);
        font-weight: 600;
    }
    .btn-outline {
        background: transparent;
        border: 1px solid #D6D6D6;
        color: #555;
        border-radius: 8px;
        padding: 0.85rem 1rem;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
        transition: all 0.2s;
    }
    .btn-outline:hover {
        background: #F5F5F5;
        color: var(--text-dark);
    }

    /* Custom Calendar CSS */
    .cal-wrap{position:relative}
    .cal-trigger{display:flex;align-items:center;gap:.6rem;padding:.55rem .9rem;
        border:1px solid #EBEBEB;border-radius:8px;background:#fff;
        cursor:pointer;transition:border-color .2s;min-height:42px}
    .cal-trigger:hover,.cal-trigger.open{border-color:var(--brand-gold)}
    .cal-trigger .cal-val{font-size:.88rem;color:var(--text-dark);font-weight:500}
    .cal-trigger .cal-placeholder{font-size:.88rem;color:#666}

    .cal-popup {
        position: fixed;
        z-index: 99999;
        background: #fff;
        border: 1px solid #EBEBEB;
        border-radius: 8px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.18);
        padding: 1rem;
        width: 320px;
        display: none;
    }
    .cal-popup.show { display: block !important; }

    .cal-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem}
    .cal-nav{background:none;border:1.5px solid #EBEBEB;border-radius:50%;width:28px;height:28px;
        display:grid;place-items:center;cursor:pointer;color:#666;transition:all .2s}
    .cal-nav:hover{border-color:var(--brand-gold);color:var(--brand-gold)}
    .cal-month-label{font-family:'Cormorant Garamond',serif;font-size:1rem;font-weight:600}

    .cal-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:2px}
    .cal-dow{text-align:center;font-size:.65rem;font-weight:600;color:#aaa;
        text-transform:uppercase;padding:.25rem 0}
    .cal-day{text-align:center;padding:.3rem .1rem;font-size:.82rem;border-radius:6px;
        cursor:pointer;transition:all .15s;position:relative;line-height:1.4}
    .cal-day:hover:not(.cal-disabled):not(.cal-booked):not(.cal-pending){
        background:#F5ECD9;color:var(--brand-gold)}
    .cal-day.cal-today{font-weight:700;color:var(--brand-gold)}
    .cal-day.cal-selected{background:var(--brand-gold)!important;color:#fff!important;font-weight:600}
    .cal-day.cal-in-range{background:#F5ECD9;color:var(--brand-gold)}
    .cal-day.cal-disabled{color:#aaa;opacity:.35;cursor:default;pointer-events:none}
    .cal-day.cal-other-month{opacity:.25;pointer-events:none}

    /* Status warna */
    .cal-day.cal-booked{background:#fee2e2;color:#ef4444;cursor:not-allowed;font-weight:600}
    .cal-day.cal-booked::after{content:'';position:absolute;bottom:3px;left:50%;
        transform:translateX(-50%);width:4px;height:4px;border-radius:50%;background:#ef4444}
    .cal-day.cal-pending{background:#fef9c3;color:#ca8a04;cursor:not-allowed;font-weight:600}
    .cal-day.cal-pending::after{content:'';position:absolute;bottom:3px;left:50%;
        transform:translateX(-50%);width:4px;height:4px;border-radius:50%;background:#eab308}

    .cal-legend{display:flex;gap:.75rem;margin-top:.75rem;padding-top:.75rem;
        border-top:1px solid #EBEBEB;flex-wrap:wrap}
    .cal-legend-item{display:flex;align-items:center;gap:.35rem;font-size:.7rem;color:#666}
    .cal-legend-dot{width:10px;height:10px;border-radius:3px;flex-shrink:0}

</style>
@endpush

@section('content')
    <h1 class="page-title">Self-Manual <em>Booking</em></h1>

    <div class="form-card">
        <div class="form-body">
            <!-- Warning Box -->
            <div class="info-alert">
                <i class="bi bi-info-circle"></i>
                <div>System will automatically check date availability and block the calendar once you save this reservation.</div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger" style="background:#fee2e2; color:#ef4444; padding:1rem; border-radius:8px; margin-bottom:1rem;">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('admin.manual_booking.store') }}" method="POST">
                @csrf

                
                <div class="row row-form">
                    <!-- Hidden inputs for backend/JS -->
                    <input type="hidden" name="check_in"  id="check_in"  required>
                    <input type="hidden" name="check_out" id="check_out" required>

                    <div class="col-md-6 mb-3 mb-md-0">
                        <label class="form-label">Check-in Date</label>
                        <div class="cal-wrap" id="wrap-ci">
                            <div class="cal-trigger" id="trigger-ci" onclick="openCal('ci')">
                                <i class="bi bi-calendar3" style="font-size:.85rem; color: var(--brand-gold);"></i>
                                <span class="cal-placeholder" id="display-ci">dd/mm/yyyy</span>
                            </div>
                            <div class="cal-popup" id="popup-ci"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Corrected label from mockup (it said Check-in Date twice) -->
                        <label class="form-label">Check-out Date</label>
                        <div class="cal-wrap" id="wrap-co">
                            <div class="cal-trigger" id="trigger-co" onclick="openCal('co')">
                                <i class="bi bi-calendar3" style="font-size:.85rem; color: var(--brand-gold);"></i>
                                <span class="cal-placeholder" id="display-co">dd/mm/yyyy</span>
                            </div>
                            <div class="cal-popup" id="popup-co"></div>
                        </div>
                    </div>
                </div>

                <div class="row row-form">
                    <div class="col-12">
                        <label class="form-label">Full Guest Name</label>
                        <input type="text" class="form-control" name="guest_name" placeholder="e.g. John Doe" required>
                    </div>
                </div>

                <div class="row row-form mb-0">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label class="form-label">Phone / WhatsApp</label>
                        <input type="text" class="form-control" name="phone" placeholder="e.g. +62 812..." required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Number of Guests</label>
                        <input type="number" class="form-control" name="guests" placeholder="1" min="1" max="10" required>
                    </div>
                </div>

        </div>
        
        <div class="form-footer">
            <button type="submit" class="btn-submit">Check & Make a reservation</button>
        </div>
        </form>
    </div>

    <!-- Success Modal -->
    <div class="success-modal-overlay" id="successModal">
        <div class="success-modal-content">
            <div class="success-icon">
                <i class="bi bi-check"></i>
            </div>
            <h3 class="success-title">Reservation Created!</h3>
            
            <div class="reservation-details">
                <div class="mb-2" id="modalRefContainer"><strong>Reference:</strong> SWM-2026-000102</div>
                <div class="mb-2"><strong>Guest:</strong> <span id="modalGuestName">John Doe</span></div>
                <div><strong>Timeline:</strong> <span id="modalCheckIn">...</span> &mdash; <span id="modalCheckOut">...</span></div>
            </div>

            <!-- In a real app, this goes to the booking list -->
            <button class="btn-submit" style="width:100%; margin-bottom:0.75rem" onclick="location.href='{{ route('admin.dashboard') }}'">Go to Booking List</button>
            <button class="btn-outline" onclick="closeModal()">Create New Booking</button>
        </div>
    </div>

    <script>
        @if(session('success'))
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('modalGuestName').textContent = "{{ session('guest_name') }}";
            document.getElementById('modalCheckIn').textContent = "{{ session('check_in') }}";
            document.getElementById('modalCheckOut').textContent = "{{ session('check_out') }}";
            document.getElementById('modalRefContainer').innerHTML = "<strong>Reference:</strong> {{ session('booking_code') }}";
            
            document.getElementById('successModal').classList.add('active');
        });
        @endif

        function closeModal() {
            document.getElementById('successModal').classList.remove('active');
            // Empty the form so they can type a new one
            document.querySelector('form').reset();
            // Reset custom calendar
            document.getElementById('check_in').value = '';
            document.getElementById('check_out').value = '';
            document.getElementById('display-ci').textContent = 'dd/mm/yyyy';
            document.getElementById('display-co').textContent = 'dd/mm/yyyy';
            calState.selectedCI = null;
            calState.selectedCO = null;
        }
    
    // Custom Calendar Engine
    const fmtD = s => { if (!s) return '— select'; const d = new Date(s + 'T00:00:00'); return d.toLocaleDateString('en-GB', { day:'numeric', month:'short', year:'numeric' }); };
    const fmtDisplay = s => {
        if (!s) return 'dd/mm/yyyy';
        const [y, m, dd] = s.split('-');
        return `${dd}/${m}/${y}`;
    };
    
    /* ══════════════════════════════════════════════════════════
       CUSTOM CALENDAR ENGINE — Fixed & Robust
    ══════════════════════════════════════════════════════════ */
    let unavailDates = {};
    let calState = {
        which: null,
        year:  new Date().getFullYear(),
        month: new Date().getMonth(),
        selectedCI: null,
        selectedCO: null,
    };
    
    const MONTHS = ['January','February','March','April','May','June',
                    'July','August','September','October','November','December'];
    const DAYS   = ['Su','Mo','Tu','We','Th','Fr','Sa'];
    
    function openCal(which) {
        const otherWhich = which === 'ci' ? 'co' : 'ci';
        const otherPopup = document.getElementById('popup-' + otherWhich);
        if (otherPopup.classList.contains('show')) {
            otherPopup.classList.remove('show');
            document.getElementById('trigger-' + otherWhich).classList.remove('open');
        }
    
        const popup = document.getElementById('popup-' + which);
    
        if (popup.classList.contains('show') && calState.which === which) {
            closeCals();
            return;
        }
    
        // ── TELEPORT ke body supaya tidak terpotong parent ──
        if (popup.parentElement !== document.body) {
            document.body.appendChild(popup);
        }
    
        calState.which = which;
    
        let ref = new Date();
        if (which === 'ci') {
            if (calState.selectedCI) ref = new Date(calState.selectedCI + 'T00:00:00');
        } else {
            if (calState.selectedCO) {
                ref = new Date(calState.selectedCO + 'T00:00:00');
            } else if (calState.selectedCI) {
                ref = new Date(calState.selectedCI + 'T00:00:00');
                ref.setDate(ref.getDate() + 1);
            }
        }
        calState.year  = ref.getFullYear();
        calState.month = ref.getMonth();
    
        renderCal(which);
        positionPopup(which);
    
        popup.classList.add('show');
        document.getElementById('trigger-' + which).classList.add('open');
    }
    
    function positionPopup(which) {
        const trigger = document.getElementById('trigger-' + which);
        const popup   = document.getElementById('popup-' + which);
        const rect    = trigger.getBoundingClientRect();
    
        // Set dulu supaya bisa diukur
        popup.style.visibility = 'hidden';
        popup.style.display    = 'block';
        popup.style.position   = 'fixed';
        popup.style.left       = rect.left + 'px';
        popup.style.top        = (rect.bottom + 6) + 'px';
    
        requestAnimationFrame(() => {
            const pw = popup.offsetWidth;
            const ph = popup.offsetHeight;
    
            let left = rect.left;
            let top  = rect.bottom + 6;
    
            // Overflow kanan
            if (left + pw > window.innerWidth - 10) {
                left = Math.max(10, window.innerWidth - pw - 10);
            }
            // Overflow bawah → munculkan ke atas trigger
            if (top + ph > window.innerHeight - 10) {
                top = rect.top - ph - 6;
                if (top < 10) top = 10;
            }
    
            popup.style.left       = left + 'px';
            popup.style.top        = top  + 'px';
            popup.style.visibility = 'visible';
            popup.style.display    = ''; // kembalikan ke CSS (show class yang kontrol)
        });
    }
    
    function closeCals() {
        ['ci','co'].forEach(w => {
            document.getElementById('popup-' + w).classList.remove('show');
            document.getElementById('trigger-' + w).classList.remove('open');
        });
        calState.which = null;
    }
    
    // ── Render kalender menggunakan DOM (bukan innerHTML string) ──
    function renderCal(which) {
        const popup  = document.getElementById('popup-' + which);
        const today  = new Date(); today.setHours(0,0,0,0);
        const y      = calState.year;
        const m      = calState.month;
        const first  = new Date(y, m, 1).getDay();
        const total  = new Date(y, m + 1, 0).getDate();
        const prevTotal = new Date(y, m, 0).getDate();
    
        const minDate = (which === 'co' && calState.selectedCI)
            ? (() => { const d = new Date(calState.selectedCI + 'T00:00:00'); d.setDate(d.getDate()+1); return d; })()
            : today;
    
        // Bersihkan popup
        popup.innerHTML = '';
    
        // ── Header navigasi ──
        const header = document.createElement('div');
        header.style.cssText = 'display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem';
    
        const btnPrev = document.createElement('button');
        btnPrev.type      = 'button';
        btnPrev.innerHTML = '&#8249;';
        btnPrev.style.cssText = 'background:none;border:1.5px solid #ddd;border-radius:50%;width:32px;height:32px;cursor:pointer;font-size:1.1rem;line-height:1;display:flex;align-items:center;justify-content:center;color:#666;transition:all .2s';
        btnPrev.onmouseenter = () => { btnPrev.style.borderColor='#b8924a'; btnPrev.style.color='#b8924a'; };
        btnPrev.onmouseleave = () => { btnPrev.style.borderColor='#ddd';    btnPrev.style.color='#666'; };
        btnPrev.addEventListener('click', function(e) {
            e.stopPropagation();
            calState.month--;
            if (calState.month < 0) { calState.month = 11; calState.year--; }
            renderCal(which);
        });
    
        const btnNext = document.createElement('button');
        btnNext.type      = 'button';
        btnNext.innerHTML = '&#8250;';
        btnNext.style.cssText = btnPrev.style.cssText;
        btnNext.onmouseenter = () => { btnNext.style.borderColor='#b8924a'; btnNext.style.color='#b8924a'; };
        btnNext.onmouseleave = () => { btnNext.style.borderColor='#ddd';    btnNext.style.color='#666'; };
        btnNext.addEventListener('click', function(e) {
            e.stopPropagation();
            calState.month++;
            if (calState.month > 11) { calState.month = 0; calState.year++; }
            renderCal(which);
        });
    
        const monthLabel = document.createElement('div');
        monthLabel.style.cssText = "font-family:'Cormorant Garamond',serif;font-size:1.05rem;font-weight:600;color:#3a3028";
        monthLabel.textContent   = MONTHS[m] + ' ' + y;
    
        header.appendChild(btnPrev);
        header.appendChild(monthLabel);
        header.appendChild(btnNext);
        popup.appendChild(header);
    
        // ── Grid ──
        const grid = document.createElement('div');
        grid.style.cssText = 'display:grid;grid-template-columns:repeat(7,1fr);gap:2px;min-width:280px';
    
        // Day headers
        DAYS.forEach(d => {
            const el = document.createElement('div');
            el.style.cssText = 'text-align:center;font-size:.65rem;font-weight:600;color:#aaa;padding:.25rem 0;text-transform:uppercase';
            el.textContent = d;
            grid.appendChild(el);
        });
    
        // Trailing dari bulan sebelumnya
        for (let i = first - 1; i >= 0; i--) {
            const el = document.createElement('div');
            el.style.cssText = 'text-align:center;padding:.35rem .1rem;font-size:.82rem;opacity:.2;line-height:1.6';
            el.textContent = prevTotal - i;
            grid.appendChild(el);
        }
    
        // Hari di bulan ini
        for (let day = 1; day <= total; day++) {
            const mm      = String(m + 1).padStart(2, '0');
            const dd      = String(day).padStart(2, '0');
            const dateStr = `${y}-${mm}-${dd}`;
            const dateObj = new Date(y, m, day);
    
            const isPast    = dateObj < minDate;
            const uStatus   = unavailDates[dateStr];
            const isBooked  = uStatus === 'CONFIRMED';
            const isPending = uStatus === 'PENDING';
            const isSelCI   = dateStr === calState.selectedCI;
            const isSelCO   = dateStr === calState.selectedCO;
            const inRange   = calState.selectedCI && calState.selectedCO
                              && dateStr > calState.selectedCI && dateStr < calState.selectedCO;
            const isToday   = dateObj.getTime() === today.getTime();
    
            const el = document.createElement('div');
            el.style.cssText = `text-align:center;padding:.35rem .1rem;font-size:.82rem;
                border-radius:6px;line-height:1.6;transition:background .15s;position:relative`;
            el.textContent = day;
    
            if (isSelCI || isSelCO) {
                el.style.background = '#b8924a';
                el.style.color      = '#fff';
                el.style.fontWeight = '600';
                el.style.cursor     = 'pointer';
            } else if (inRange) {
                el.style.background = '#f5ecd9';
                el.style.color      = '#b8924a';
                el.style.cursor     = 'pointer';
            } else if (isBooked) {
                el.style.background = '#fee2e2';
                el.style.color      = '#ef4444';
                el.style.fontWeight = '600';
                el.style.cursor     = 'not-allowed';
                // Dot indicator
                const dot = document.createElement('span');
                dot.style.cssText = 'position:absolute;bottom:3px;left:50%;transform:translateX(-50%);width:4px;height:4px;border-radius:50%;background:#ef4444;display:block';
                el.appendChild(dot);
            } else if (isPending) {
                el.style.background = '#fef9c3';
                el.style.color      = '#ca8a04';
                el.style.fontWeight = '600';
                el.style.cursor     = 'not-allowed';
                const dot = document.createElement('span');
                dot.style.cssText = 'position:absolute;bottom:3px;left:50%;transform:translateX(-50%);width:4px;height:4px;border-radius:50%;background:#eab308;display:block';
                el.appendChild(dot);
            } else if (isPast) {
                el.style.color  = '#ccc';
                el.style.cursor = 'default';
            } else {
                // Tanggal tersedia
                el.style.cursor = 'pointer';
                if (isToday) {
                    el.style.fontWeight = '700';
                    el.style.border     = '1.5px solid #b8924a';
                    el.style.color      = '#b8924a';
                } else {
                    el.style.color = '#3a3028';
                }
                el.addEventListener('mouseenter', () => {
                    el.style.background = '#f5ecd9';
                    el.style.color      = '#b8924a';
                });
                el.addEventListener('mouseleave', () => {
                    el.style.background = 'transparent';
                    el.style.color      = isToday ? '#b8924a' : '#3a3028';
                });
                el.addEventListener('click', function(e) {
                    e.stopPropagation();
                    pickDate(dateStr, which);
                });
            }
    
            grid.appendChild(el);
        }
    
        // Trailing ke bulan berikutnya
        const filled    = first + total;
        const remainder = filled % 7 === 0 ? 0 : 7 - (filled % 7);
        for (let i = 1; i <= remainder; i++) {
            const el = document.createElement('div');
            el.style.cssText = 'text-align:center;padding:.35rem .1rem;font-size:.82rem;opacity:.2;line-height:1.6';
            el.textContent = i;
            grid.appendChild(el);
        }
    
        popup.appendChild(grid);
    
        // ── Legend ──
        const legend = document.createElement('div');
        legend.style.cssText = 'display:flex;gap:.75rem;margin-top:.75rem;padding-top:.75rem;border-top:1px solid #eee;flex-wrap:wrap';
        const legendItems = [
            { bg:'#fee2e2', border:'#ef4444', label:'Fully Booked' },
            { bg:'#fef9c3', border:'#eab308', label:'Pending'      },
            { bg:'#b8924a', border:'',        label:'Selected'     },
        ];
        legendItems.forEach(li => {
            const item = document.createElement('div');
            item.style.cssText = 'display:flex;align-items:center;gap:.35rem;font-size:.7rem;color:#999';
            const dot  = document.createElement('div');
            dot.style.cssText = `width:10px;height:10px;border-radius:3px;background:${li.bg};flex-shrink:0${li.border ? ';border:1.5px solid '+li.border : ''}`;
            item.appendChild(dot);
            item.appendChild(document.createTextNode(li.label));
            legend.appendChild(item);
        });
        popup.appendChild(legend);
    }
    
    function pickDate(dateStr, which) {
        if (which === 'ci') {
            calState.selectedCI = dateStr;
    
            // Reset check-out kalau sebelum/sama dengan check-in baru
            if (calState.selectedCO && calState.selectedCO <= dateStr) {
                calState.selectedCO = null;
                document.getElementById('check_out').value = '';
                const dco = document.getElementById('display-co');
                dco.textContent  = 'dd/mm/yyyy';
                dco.className    = 'cal-placeholder';
            }
    
            document.getElementById('check_in').value = dateStr;
            const dci = document.getElementById('display-ci');
            dci.textContent = fmtDisplay(dateStr);
            dci.className   = 'cal-val';
    
            closeCals();
            // Buka otomatis checkout setelah 150ms
            setTimeout(() => openCal('co'), 150);
    
        } else {
            calState.selectedCO = dateStr;
            document.getElementById('check_out').value = dateStr;
            const dco = document.getElementById('display-co');
            dco.textContent = fmtDisplay(dateStr);
            dco.className   = 'cal-val';
    
            // Re-render check-in popup kalau terbuka supaya range terupdate
            if (calState.selectedCI) {
                // Refresh range highlight di check-in trigger area
            }
            closeCals();
        }
        calc();
    }
    
    // Tutup kalau klik di luar
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.cal-wrap') && !e.target.closest('.cal-popup')) {
            closeCals();
        }
    });
    
    // Prevent scroll saat kalender terbuka
    document.addEventListener('wheel', function(e) {
        if (calState.which) {
            const popup = document.getElementById('popup-' + calState.which);
            if (popup && popup.classList.contains('show') && !popup.contains(e.target)) {
                e.preventDefault();
            }
        }
    }, { passive: false });
    
    /* ══════════════════════════════════════════════════════════
       LOAD UNAVAILABLE DATES FROM BACKEND
    ══════════════════════════════════════════════════════════ */
    fetch('/api/unavailable-dates')
        .then(r => {
            if (!r.ok) throw new Error(`API ${r.status}`);
            return r.json();
        })
        .then(data => {
            console.log('✓ Unavailable dates:', Object.keys(data).length);
            unavailDates = data;
        })
        .catch(err => {
            console.warn('Unavailable dates not loaded:', err.message);
            unavailDates = {};
        });
    
    function calc() { }
</script>
@endsection
