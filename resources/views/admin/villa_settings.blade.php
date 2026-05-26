@extends('layouts.admin')

@push('styles')
<style>
.page-title{font-family:'Cormorant Garamond',serif;font-size:2.2rem;color:var(--text-dark);margin-bottom:2.5rem;text-align:center}
.page-title em{font-style:italic;color:var(--brand-gold-dark)}
.form-card{background:#fff;border-radius:12px;box-shadow:0 4px 15px rgba(0,0,0,.03);border:1px solid rgba(0,0,0,.05);overflow:hidden;max-width:900px;margin:0 auto}
.form-body{padding:2.5rem}
.form-footer{background:#F8F6F2;padding:1.5rem 2.5rem;display:flex;justify-content:flex-end;border-top:1px solid rgba(0,0,0,.05)}
.info-alert{background:#FDFBF7;border:1px solid #EBE4D5;border-radius:8px;padding:1.25rem;display:flex;align-items:center;gap:.8rem;font-size:.85rem;color:#7A6953;margin-bottom:2rem}
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
.status-cell{display:flex;align-items:center;gap:.5rem}
.status-info{font-size:.8rem;color:#666}
.btn-action{padding:.3rem .6rem;border:none;border-radius:6px;font-size:.75rem;font-weight:600;cursor:pointer;transition:all .2s}
.btn-edit{background:#bfdbfe;color:#1e40af}
.btn-edit:hover{background:#93c5fd}
.btn-delete{background:#fecaca;color:#991b1b}
.btn-delete:hover{background:#fca5a5}
</style>
@endpush

@section('content')
<h1 class="page-title">Configuration <em>List</em></h1>

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
            <div class="row row-form">
                <div class="col-12">
                    <label class="form-label">Price per Night (Rp)</label>
                    <input type="number" class="form-control" name="base_price"
                           placeholder="e.g. 4500000" value="{{ old('base_price') }}" required>
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
    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- PROMO FORM ────────────────────────────────────────────────── --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <form action="{{ route('admin.villa_settings.save') }}" method="POST">
        @csrf
        <div class="form-body">
            @if(session('success'))
            <div style="background:#edf7ed;color:#1e4620;padding:1rem 1.25rem;border-radius:8px;margin-bottom:1.5rem;font-size:.85rem;font-weight:600;display:flex;align-items:center;gap:.75rem">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div style="background:#fef2f2;color:#991b1b;padding:1rem 1.25rem;border-radius:8px;margin-bottom:1.5rem;font-size:.85rem">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Please fix:</strong>
                <ul style="margin:.5rem 0 0 1rem;padding:0">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
            @endif

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
                    <label class="form-label">Promo Code <small style="color:#b8924a">(diketik tamu)</small></label>
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
            @php 
                $promos = \App\Models\Promo::orderByDesc('created_at')->get();
                $now = \Carbon\Carbon::now();
                $today = \Carbon\Carbon::now()->toDateString();
            @endphp
            
            @if($promos->isEmpty())
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
                                $startDate = \Carbon\Carbon::parse($p->valid_from);
                                $endDate = \Carbon\Carbon::parse($p->valid_until);
                                $daysLeft = $endDate->diffInDays($now, false);
                                $isExpired = $endDate->lt($now);
                                $isNotStarted = $startDate->gt($now);
                                $isActive = !$isExpired && !$isNotStarted && $p->is_active;
                                $expiringSoon = $daysLeft <= 7 && $daysLeft > 0 && $isActive;
                            @endphp
                        <tr style="opacity: {{ $isExpired ? '0.6' : '1' }};">
                            <td><strong>{{ $p->code }}</strong></td>
                            <td>{{ $p->name }}</td>
                            <td><span style="background:#f0f0f0;padding:.2rem .5rem;border-radius:4px;font-weight:600">{{ $p->discount_percent }}%</span></td>
                            <td style="font-size:.8rem;color:#666">
                                {{ $startDate->format('d M Y') }} → {{ $endDate->format('d M Y') }}
                                <br/>
                                @if($isNotStarted)
                                    <span style="color:#f59e0b">Starts in {{ $startDate->diffInDays($now) }} days</span>
                                @endif
                            </td>
                            <td>
                                <div class="status-cell">
                                    @if($isExpired)
                                        <span class="badge-expired">Expired</span>
                                        <span class="status-info">(ended {{ $endDate->diffInDays($now) }} days ago)</span>
                                    @elseif($isNotStarted)
                                        <span style="background:#bfdbfe;color:#1e40af;padding:.25rem .7rem;border-radius:50px;font-size:.72rem;font-weight:600">Pending</span>
                                        <span class="status-info">(starts in {{ $startDate->diffInDays($now) }} days)</span>
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
});

async function editPromo(id) {
    try {
        const response = await fetch(`/admin/promo/${id}/edit`);
        const promo = await response.json();
        
        // Populate form
        document.getElementById('edit-promo-id').value = promo.id;
        document.getElementById('edit-promo-name').value = promo.name;
        document.getElementById('edit-discount').value = promo.discount_percent;
        document.getElementById('edit-valid-from').value = promo.valid_from.split(' ')[0];
        document.getElementById('edit-valid-until').value = promo.valid_until.split(' ')[0];
        document.getElementById('edit-promo-status').value = promo.is_active ? 'active' : 'inactive';
        
        // Show modal
        document.getElementById('editPromoModal').style.display = 'flex';
    } catch (error) {
        alert('Error loading promo');
        console.error(error);
    }
}

function closeEditModal() {
    document.getElementById('editPromoModal').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('editPromoModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}
</script>

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
                <input type="text" id="edit-promo-name" style="width:100%;padding:.8rem 1rem;border:1px solid #EBEBEB;border-radius:8px;font-size:.9rem" required>
            </div>
            
            <div>
                <label style="display:block;font-size:.8rem;font-weight:600;color:#888;margin-bottom:.5rem">Discount (%)</label>
                <input type="number" id="edit-discount" min="1" max="100" style="width:100%;padding:.8rem 1rem;border:1px solid #EBEBEB;border-radius:8px;font-size:.9rem" required>
            </div>
            
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:600;color:#888;margin-bottom:.5rem">Valid From</label>
                    <input type="date" id="edit-valid-from" style="width:100%;padding:.8rem 1rem;border:1px solid #EBEBEB;border-radius:8px;font-size:.9rem" required>
                </div>
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:600;color:#888;margin-bottom:.5rem">Valid Until</label>
                    <input type="date" id="edit-valid-until" style="width:100%;padding:.8rem 1rem;border:1px solid #EBEBEB;border-radius:8px;font-size:.9rem" required>
                </div>
            </div>
            
            <div>
                <label style="display:block;font-size:.8rem;font-weight:600;color:#888;margin-bottom:.5rem">Status</label>
                <select id="edit-promo-status" style="width:100%;padding:.8rem 1rem;border:1px solid #EBEBEB;border-radius:8px;font-size:.9rem" required>
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