@extends('layouts.admin')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 style="font-family:'Cormorant Garamond',serif; font-size:1.8rem; font-weight:600; color:#333; margin:0">Home Content</h1>
        <p style="color:#888; font-size:0.85rem; margin:0">Kelola konten Hero Section halaman utama</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border:none; background:#d4edda; border-radius:10px; font-size:0.9rem">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('admin.home_content.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">

        {{-- LEFT: Text Content --}}
        <div class="col-lg-6">
            <div class="bg-white rounded-3 p-4 shadow-sm h-100">
                <h6 style="font-weight:700; font-size:0.75rem; letter-spacing:0.08em; text-transform:uppercase; color:#888; margin-bottom:1.5rem">
                    <i class="bi bi-type me-2" style="color:var(--brand-gold)"></i>TEXT CONTENT
                </h6>

                <div class="mb-4">
                    <label class="form-label" style="font-weight:600; font-size:0.85rem">Hero Title</label>
                    <input type="text" name="hero_title" class="form-control @error('hero_title') is-invalid @enderror"
                        value="{{ old('hero_title', $content->get('hero_title')?->value ?? 'Swarna Mandapa') }}"
                        style="border-radius:8px; border-color:#e0e0e0; font-size:0.9rem">
                    @error('hero_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label" style="font-weight:600; font-size:0.85rem">Hero Subtitle</label>
                    <textarea name="hero_subtitle" rows="3" class="form-control @error('hero_subtitle') is-invalid @enderror"
                        style="border-radius:8px; border-color:#e0e0e0; font-size:0.9rem; resize:vertical">{{ old('hero_subtitle', $content->get('hero_subtitle')?->value ?? '') }}</textarea>
                    @error('hero_subtitle')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label" style="font-weight:600; font-size:0.85rem">Button Text</label>
                    <input type="text" name="hero_button_text" class="form-control @error('hero_button_text') is-invalid @enderror"
                        value="{{ old('hero_button_text', $content->get('hero_button_text')?->value ?? 'Check Availability →') }}"
                        style="border-radius:8px; border-color:#e0e0e0; font-size:0.9rem">
                    @error('hero_button_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Preview card --}}
                <div style="background: linear-gradient(135deg, #1a1a1a, #3d2b0f); border-radius:12px; padding:2rem; text-align:center; color:white; margin-top:1rem">
                    <p style="font-size:0.65rem; letter-spacing:0.1em; opacity:0.5; margin-bottom:0.75rem; text-transform:uppercase">Preview</p>
                    <h2 id="preview-title" style="font-family:'Cormorant Garamond',serif; color:#ffdc7d; font-size:1.4rem; margin-bottom:0.5rem">
                        {{ $content->get('hero_title')?->value ?? 'Swarna Mandapa' }}
                    </h2>
                    <p id="preview-subtitle" style="font-size:0.8rem; opacity:0.8; margin-bottom:1rem">
                        {{ $content->get('hero_subtitle')?->value ?? '' }}
                    </p>
                    <span id="preview-button" style="background:#B8924A; color:white; padding:0.4rem 1.2rem; border-radius:999px; font-size:0.8rem">
                        {{ $content->get('hero_button_text')?->value ?? 'Check Availability →' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- RIGHT: Media Upload --}}
        <div class="col-lg-6">

            {{-- Hero Video --}}
            <div class="bg-white rounded-3 p-4 shadow-sm mb-4">
                <h6 style="font-weight:700; font-size:0.75rem; letter-spacing:0.08em; text-transform:uppercase; color:#888; margin-bottom:1.5rem">
                    <i class="bi bi-camera-video me-2" style="color:var(--brand-gold)"></i>HERO BACKGROUND VIDEO
                </h6>

                @php $videoUrl = $content->get('hero_video_url')?->value; @endphp
                @if($videoUrl)
                    <div class="mb-3">
                        <p style="font-size:0.8rem; color:#888; margin-bottom:0.5rem">Video saat ini:</p>
                        <video src="{{ $videoUrl }}" style="width:100%; border-radius:8px; max-height:160px; object-fit:cover" muted loop autoplay></video>
                        <p style="font-size:0.72rem; color:#aaa; margin-top:0.5rem; word-break:break-all">{{ $videoUrl }}</p>
                    </div>
                @else
                    <div class="mb-3 p-3 text-center" style="background:#f8f8f8; border-radius:8px; border:1px dashed #ddd">
                        <i class="bi bi-camera-video" style="font-size:1.5rem; color:#ccc"></i>
                        <p style="font-size:0.8rem; color:#aaa; margin:0.5rem 0 0">Belum ada video. Saat ini menggunakan video lokal (sm.MP4).</p>
                    </div>
                @endif

                <div>
                    <label class="form-label" style="font-weight:600; font-size:0.85rem">Upload Video Baru</label>
                    <input type="file" name="hero_video" class="form-control @error('hero_video') is-invalid @enderror"
                        accept="video/mp4,video/mov,video/avi,video/webm"
                        style="border-radius:8px; border-color:#e0e0e0; font-size:0.85rem">
                    <p class="text-muted mt-1" style="font-size:0.75rem">Format: MP4, MOV, AVI, WebM. Maks 200MB.</p>
                    @error('hero_video')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Hero Image --}}
            <div class="bg-white rounded-3 p-4 shadow-sm">
                <h6 style="font-weight:700; font-size:0.75rem; letter-spacing:0.08em; text-transform:uppercase; color:#888; margin-bottom:1.5rem">
                    <i class="bi bi-image me-2" style="color:var(--brand-gold)"></i>HERO BACKGROUND IMAGE
                    <span style="font-weight:400; font-size:0.7rem; color:#aaa">(fallback jika tidak ada video)</span>
                </h6>

                @php $imageUrl = $content->get('hero_image_url')?->value; @endphp
                @if($imageUrl)
                    <div class="mb-3">
                        <p style="font-size:0.8rem; color:#888; margin-bottom:0.5rem">Gambar saat ini:</p>
                        <img src="{{ $imageUrl }}" style="width:100%; border-radius:8px; max-height:160px; object-fit:cover" alt="Hero image">
                        <p style="font-size:0.72rem; color:#aaa; margin-top:0.5rem; word-break:break-all">{{ $imageUrl }}</p>
                    </div>
                @else
                    <div class="mb-3 p-3 text-center" style="background:#f8f8f8; border-radius:8px; border:1px dashed #ddd">
                        <i class="bi bi-image" style="font-size:1.5rem; color:#ccc"></i>
                        <p style="font-size:0.8rem; color:#aaa; margin:0.5rem 0 0">Belum ada gambar fallback.</p>
                    </div>
                @endif

                <div>
                    <label class="form-label" style="font-weight:600; font-size:0.85rem">Upload Gambar Baru</label>
                    <input type="file" name="hero_image" class="form-control @error('hero_image') is-invalid @enderror"
                        accept="image/jpg,image/jpeg,image/png,image/webp"
                        style="border-radius:8px; border-color:#e0e0e0; font-size:0.85rem">
                    <p class="text-muted mt-1" style="font-size:0.75rem">Format: JPG, PNG, WebP. Maks 10MB.</p>
                    @error('hero_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

    </div>

    <div class="mt-4 d-flex justify-content-end gap-3">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary" style="border-radius:8px; font-size:0.9rem">Batal</a>
        <button type="submit" class="btn" style="background:var(--brand-gold); color:white; border-radius:8px; font-weight:600; font-size:0.9rem; padding:0.6rem 2rem">
            <i class="bi bi-check2 me-2"></i>Simpan Perubahan
        </button>
    </div>
</form>
@endsection

@push('styles')
<style>
    .form-control:focus { border-color: var(--brand-gold); box-shadow: 0 0 0 3px rgba(184,146,74,0.15); }
</style>
@endpush

@push('scripts')
<script>
    // Live preview update
    document.querySelector('[name="hero_title"]').addEventListener('input', function() {
        document.getElementById('preview-title').textContent = this.value || 'Swarna Mandapa';
    });
    document.querySelector('[name="hero_subtitle"]').addEventListener('input', function() {
        document.getElementById('preview-subtitle').textContent = this.value;
    });
    document.querySelector('[name="hero_button_text"]').addEventListener('input', function() {
        document.getElementById('preview-button').textContent = this.value || 'Check Availability →';
    });
</script>
@endpush
