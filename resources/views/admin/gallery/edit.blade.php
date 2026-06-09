@extends('layouts.admin')

@section('content')
<div class="d-flex align-items-center mb-4 gap-3">
    <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h1 style="font-family:'Cormorant Garamond',serif; font-size:1.8rem; font-weight:600; color:#333; margin:0">Edit Media</h1>
        <p style="color:#888; font-size:0.85rem; margin:0">{{ $gallery->file_name }}</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" style="border:none; background:#d4edda; border-radius:10px; font-size:0.9rem">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    {{-- Preview --}}
    <div class="col-lg-5">
        <div class="bg-white rounded-3 shadow-sm p-4">
            <h6 style="font-weight:700; font-size:0.75rem; letter-spacing:0.08em; text-transform:uppercase; color:#888; margin-bottom:1rem">
                <i class="bi bi-eye me-2" style="color:var(--brand-gold)"></i>PREVIEW
            </h6>

            @if($gallery->media_type === 'video')
                <video src="{{ $gallery->file_url }}" controls style="width:100%; border-radius:10px; max-height:280px; background:#111"></video>
            @else
                <img src="{{ $gallery->file_url }}" alt="{{ $gallery->file_name }}"
                    style="width:100%; border-radius:10px; max-height:280px; object-fit:cover">
            @endif

            <div class="mt-3" style="background:#f8f8f8; border-radius:8px; padding:1rem; font-size:0.82rem">
                <div class="d-flex justify-content-between mb-2">
                    <span style="color:#888">Tipe</span>
                    <span class="badge" style="background:{{ $gallery->media_type === 'video' ? '#6c757d' : '#198754' }}; font-size:0.7rem">
                        {{ strtoupper($gallery->media_type) }}
                    </span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span style="color:#888">Disk</span>
                    <span style="font-weight:600; color:#333">{{ strtoupper($gallery->disk) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span style="color:#888">Diupload</span>
                    <span style="color:#333">{{ $gallery->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div style="border-top:1px solid #eee; padding-top:0.75rem; margin-top:0.5rem">
                    <p style="color:#888; font-size:0.75rem; margin-bottom:0.35rem">Storage Path</p>
                    <p style="color:#555; word-break:break-all; margin:0; font-family:monospace; font-size:0.75rem">{{ $gallery->storage_path ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Form --}}
    <div class="col-lg-7">
        <div class="bg-white rounded-3 shadow-sm p-4">
            <h6 style="font-weight:700; font-size:0.75rem; letter-spacing:0.08em; text-transform:uppercase; color:#888; margin-bottom:1.5rem">
                <i class="bi bi-pencil me-2" style="color:var(--brand-gold)"></i>EDIT INFORMASI
            </h6>

            <form action="{{ route('admin.gallery.update', $gallery) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="mb-4">
                    <label class="form-label" style="font-weight:600; font-size:0.85rem">Nama File</label>
                    <input type="text" name="file_name" class="form-control @error('file_name') is-invalid @enderror"
                        value="{{ old('file_name', $gallery->file_name) }}"
                        style="border-radius:8px; border-color:#e0e0e0; font-size:0.9rem">
                    @error('file_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label" style="font-weight:600; font-size:0.85rem">Kategori</label>
                    <select name="category" class="form-select @error('category') is-invalid @enderror"
                        style="border-radius:8px; border-color:#e0e0e0; font-size:0.9rem">
                        @foreach($categories as $slug => $label)
                            <option value="{{ $slug }}" {{ old('category', $gallery->category) === $slug ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label" style="font-weight:600; font-size:0.85rem">Urutan (Sort Order)</label>
                    <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror"
                        value="{{ old('sort_order', $gallery->sort_order) }}" min="0"
                        style="border-radius:8px; border-color:#e0e0e0; font-size:0.9rem">
                    <p class="text-muted mt-1" style="font-size:0.75rem">Angka lebih kecil = tampil lebih dahulu di gallery.</p>
                    @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label" style="font-weight:600; font-size:0.85rem">URL File</label>
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ $gallery->file_url }}" readonly
                            style="border-radius:8px 0 0 8px; border-color:#e0e0e0; font-size:0.82rem; background:#f8f8f8; font-family:monospace">
                        <button type="button" class="btn btn-outline-secondary"
                            style="border-radius:0 8px 8px 0; font-size:0.8rem"
                            onclick="navigator.clipboard.writeText('{{ $gallery->file_url }}'); this.innerHTML='<i class=\'bi bi-check\'></i>'">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <form action="{{ route('admin.gallery.destroy', $gallery) }}" method="POST"
                        onsubmit="return confirm('Hapus file ini secara permanen? File akan dihapus dari storage juga.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" style="border-radius:8px; font-size:0.85rem">
                            <i class="bi bi-trash me-2"></i>Hapus Media
                        </button>
                    </form>

                    <button type="submit" class="btn" style="background:var(--brand-gold); color:white; border-radius:8px; font-weight:600; font-size:0.9rem; padding:0.6rem 2rem">
                        <i class="bi bi-check2 me-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-control:focus, .form-select:focus { border-color:var(--brand-gold); box-shadow:0 0 0 3px rgba(184,146,74,0.15); }
</style>
@endpush
