@extends('layouts.admin')

@push('styles')
<style>
    .media-card { background:white; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.05); transition:transform 0.2s; }
    .media-card:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,0,0,0.1); }
    .media-thumb { width:100%; height:140px; object-fit:cover; display:block; }
    .stat-card { background:white; border-radius:12px; padding:1.25rem 1.5rem; box-shadow:0 2px 8px rgba(0,0,0,0.05); }
    .form-control:focus, .form-select:focus { border-color:var(--brand-gold); box-shadow:0 0 0 3px rgba(184,146,74,0.15); }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 style="font-family:'Cormorant Garamond',serif; font-size:1.8rem; font-weight:600; color:#333; margin:0">Media Library</h1>
        <p style="color:#888; font-size:0.85rem; margin:0">Semua file yang pernah diupload</p>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-4">
        <div class="stat-card">
            <p style="font-size:0.72rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#aaa; margin:0">Total File</p>
            <p style="font-size:1.8rem; font-weight:700; color:#333; margin:0; line-height:1.2">{{ $totalCount }}</p>
        </div>
    </div>
    <div class="col-4">
        <div class="stat-card">
            <p style="font-size:0.72rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#aaa; margin:0">Gambar</p>
            <p style="font-size:1.8rem; font-weight:700; color:#198754; margin:0; line-height:1.2">{{ $imageCount }}</p>
        </div>
    </div>
    <div class="col-4">
        <div class="stat-card">
            <p style="font-size:0.72rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#aaa; margin:0">Video</p>
            <p style="font-size:1.8rem; font-weight:700; color:#6c757d; margin:0; line-height:1.2">{{ $videoCount }}</p>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white rounded-3 shadow-sm p-3 mb-4">
    <form action="{{ route('admin.media_library') }}" method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Cari nama file..."
                value="{{ $search }}" style="border-radius:8px; border-color:#e0e0e0; font-size:0.875rem">
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select" style="border-radius:8px; border-color:#e0e0e0; font-size:0.875rem">
                <option value="">Semua Kategori</option>
                @foreach($categories as $slug => $label)
                    <option value="{{ $slug }}" {{ $category === $slug ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="type" class="form-select" style="border-radius:8px; border-color:#e0e0e0; font-size:0.875rem">
                <option value="">Semua Tipe</option>
                <option value="image" {{ $type === 'image' ? 'selected' : '' }}>Gambar</option>
                <option value="video" {{ $type === 'video' ? 'selected' : '' }}>Video</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn flex-fill" style="background:var(--brand-gold); color:white; border-radius:8px; font-size:0.875rem">
                <i class="bi bi-search me-1"></i>Filter
            </button>
            @if($search || $category || $type)
                <a href="{{ route('admin.media_library') }}" class="btn btn-outline-secondary" style="border-radius:8px; font-size:0.875rem">
                    <i class="bi bi-x"></i>
                </a>
            @endif
        </div>
    </form>
</div>

{{-- Grid --}}
@if($media->isEmpty())
    <div class="text-center py-5 bg-white rounded-3">
        <i class="bi bi-folder2-open" style="font-size:3rem; color:#ddd; display:block; margin-bottom:1rem"></i>
        <p style="color:#aaa; font-size:0.9rem">Tidak ada media yang ditemukan.</p>
    </div>
@else
    <div class="row g-3">
        @foreach($media as $item)
            <div class="col-6 col-md-4 col-xl-3">
                <div class="media-card">
                    @if($item->media_type === 'video')
                        <div style="position:relative">
                            <video class="media-thumb" src="{{ $item->file_url }}" muted preload="metadata" style="background:#111"></video>
                            <span style="position:absolute; top:6px; left:6px; background:rgba(0,0,0,0.6); color:white; font-size:0.65rem; font-weight:700; padding:2px 7px; border-radius:999px">
                                <i class="bi bi-camera-video-fill me-1"></i>VIDEO
                            </span>
                        </div>
                    @else
                        <img class="media-thumb" src="{{ $item->file_url }}" alt="{{ $item->file_name }}" loading="lazy">
                    @endif

                    <div style="padding:0.75rem 0.875rem">
                        <p style="font-size:0.78rem; font-weight:600; color:#333; margin:0; overflow:hidden; white-space:nowrap; text-overflow:ellipsis" title="{{ $item->file_name }}">
                            {{ $item->file_name }}
                        </p>
                        <p style="font-size:0.7rem; color:#bbb; margin:0.2rem 0 0.6rem">
                            {{ $categories[$item->category] ?? $item->category }}
                        </p>
                        <p style="font-size:0.7rem; color:#ccc; margin:0">
                            {{ $item->created_at->format('d M Y') }}
                        </p>
                        <div class="d-flex gap-2 mt-2">
                            <a href="{{ route('admin.gallery.edit', $item) }}"
                               class="btn btn-sm flex-fill" style="background:#f5f0e8; color:#B8924A; border-radius:6px; font-size:0.75rem; font-weight:600">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </a>
                            <form action="{{ route('admin.gallery.destroy', $item) }}" method="POST"
                                onsubmit="return confirm('Hapus file ini?')" class="flex-fill">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm w-100" style="background:#fdf0f0; color:#dc3545; border-radius:6px; font-size:0.75rem; font-weight:600">
                                    <i class="bi bi-trash me-1"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $media->links() }}
    </div>
@endif
@endsection
