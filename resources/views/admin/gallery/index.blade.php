@extends('layouts.admin')

@push('styles')
<style>
    .media-card { position:relative; background:white; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.05); transition:transform 0.2s, box-shadow 0.2s; }
    .media-card:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,0,0,0.1); }
    .media-thumb { width:100%; height:180px; object-fit:cover; display:block; }
    .media-thumb-video { width:100%; height:180px; object-fit:cover; display:block; background:#111; }
    .media-overlay { position:absolute; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0); transition:background 0.2s; display:flex; align-items:center; justify-content:center; gap:0.5rem; opacity:0; }
    .media-card:hover .media-overlay { background:rgba(0,0,0,0.45); opacity:1; }
    .media-badge { position:absolute; top:8px; left:8px; padding:2px 8px; border-radius:999px; font-size:0.65rem; font-weight:700; letter-spacing:0.05em;}
    .category-tab { border:none; background:none; padding:0.5rem 1.25rem; font-size:0.85rem; font-weight:600; color:#888; border-bottom:2px solid transparent; transition:all 0.2s; cursor:pointer; }
    .category-tab.active { color:var(--brand-gold); border-bottom-color:var(--brand-gold); }
    .category-tab:hover { color:var(--brand-gold-dark); }
    .upload-zone { border:2px dashed #ddd; border-radius:12px; padding:2.5rem; text-align:center; cursor:pointer; transition:border-color 0.2s, background 0.2s; }
    .upload-zone:hover, .upload-zone.dragging { border-color:var(--brand-gold); background:rgba(184,146,74,0.04); }
    .form-control:focus, .form-select:focus { border-color:var(--brand-gold); box-shadow:0 0 0 3px rgba(184,146,74,0.15); }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 style="font-family:'Cormorant Garamond',serif; font-size:1.8rem;font-weight:600; color:#333; margin:0">Gallery Management</h1>
        <p style="color:#888; font-size:0.85rem; margin:0">Upload dan kelola media gallery per kategori</p>
    </div>
    <button class="btn" style="background:var(--brand-gold); color:white; border-radius:8px; font-weight:600; font-size:0.875rem"
        data-bs-toggle="modal" data-bs-target="#uploadModal">
        <i class="bi bi-cloud-upload me-2"></i>Upload Media
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" style="border:none; background:#d4edda; border-radius:10px; font-size:0.9rem">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Category Tabs --}}
<div class="bg-white rounded-3 shadow-sm mb-4" style="overflow:hidden">
    <div class="d-flex overflow-auto" style="border-bottom:1px solid #f0f0f0; padding:0 1rem">
        <a href="{{ route('admin.gallery.index') }}"
           class="category-tab {{ !$category ? 'active' : '' }}">
            All
        </a>
        @foreach($categories as $slug => $label)
            <a href="{{ route('admin.gallery.index', ['category' => $slug]) }}"
               class="category-tab {{ $category === $slug ? 'active' : '' }}"
               style="white-space:nowrap">
                {{ $label }}
            </a>
        @endforeach
    </div>
</div>

{{-- Media Grid --}}
@if($media->isEmpty())
    <div class="text-center py-5" style="background:white; border-radius:12px">
        <i class="bi bi-images" style="font-size:3rem; color:#ddd; display:block; margin-bottom:1rem"></i>
        <p style="color:#aaa; font-size:0.9rem">Belum ada media di kategori ini.</p>
        <button class="btn btn-sm mt-2" style="background:var(--brand-gold);color:white; border-radius:8px; font-size:0.85rem"
            data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="bi bi-cloud-upload me-1"></i>Upload sekarang
        </button>
    </div>
@else
    <div class="row g-3">
        @foreach($media as $item)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="media-card">
                    @if($item->media_type === 'video')
                        <video class="media-thumb-video" src="{{ $item->file_url }}" muted preload="metadata"></video>
                        <span class="media-badge" style="background:rgba(0,0,0,0.6); color:white"><i class="bi bi-camera-video-fill me-1"></i>VIDEO</span>
                    @else
                        <img class="media-thumb" src="{{ $item->file_url }}" alt="{{ $item->file_name }}" loading="lazy">
                    @endif

                    <div class="media-overlay">
                        <a href="{{ route('admin.gallery.edit', $item) }}" class="btn btn-sm btn-light" style="border-radius:6px; font-size:0.8rem">
                            <i class="bi bi-pencil"></i>
                        </a>
                        {{-- Tombol delete: buka modal konfirmasi --}}
                        <button type="button"
                            class="btn btn-sm btn-danger"
                            style="border-radius:6px; font-size:0.8rem"
                            onclick="confirmDelete('{{ route('admin.gallery.destroy', $item) }}', '{{ addslashes($item->file_name) }}')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>

                    <div style="padding:0.75rem">
                        <p style="font-size:0.78rem; font-weight:600; color:#333; margin:0; overflow:hidden; white-space:nowrap; text-overflow:ellipsis" title="{{ $item->file_name }}">
                            {{ $item->file_name }}
                        </p>
                        <p style="font-size:0.7rem; color:#aaa; margin:0.2rem 0 0">
                            {{ $categories[$item->category] ?? $item->category }}
                            &bull; #{{ $item->sort_order }}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $media->links() }}
    </div>
@endif

{{-- Upload Modal --}}
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border:none; border-radius:16px">
            <div class="modal-header" style="border-bottom:1px solid #f0f0f0; padding:1.5rem">
                <h5 class="modal-title" style="font-family:'Cormorant Garamond',serif; font-size:1.3rem; font-weight:600">
                    Upload Media
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.gallery.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" style="padding:1.5rem">

                    <div class="mb-4">
                        <label class="form-label" style="font-weight:600; font-size:0.85rem">Kategori</label>
                        <select name="category" class="form-select" style="border-radius:8px; border-color:#e0e0e0; font-size:0.9rem" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $slug => $label)
                                <option value="{{ $slug }}" {{ $category === $slug ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" style="font-weight:600; font-size:0.85rem">File Media</label>
                        <div class="upload-zone" id="dropZone" onclick="document.getElementById('fileInput').click()">
                            <i class="bi bi-cloud-arrow-up" style="font-size:2rem; color:#ccc; display:block; margin-bottom:0.75rem"></i>
                            <p style="font-size:0.9rem; color:#888; margin:0">Klik atau drag & drop file ke sini</p>
                            <p style="font-size:0.75rem; color:#bbb; margin:0.25rem 0 0">JPG, PNG, WebP, GIF, MP4, MOV, WebM – maks 200MB per file, 20 file sekaligus</p>
                            <div id="fileList" class="mt-3"></div>
                        </div>
                        <input type="file" id="fileInput" name="files[]" multiple accept="image/*,video/mp4,video/mov,video/webm" style="display:none">
                    </div>

                </div>
                <div class="modal-footer" style="border-top:1px solid #f0f0f0; padding:1rem 1.5rem">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:8px; font-size:0.9rem">Batal</button>
                    <button type="submit" class="btn" style="background:var(--brand-gold); color:white; border-radius:8px; font-weight:600; font-size:0.9rem; padding:0.6rem 1.75rem">
                        <i class="bi bi-cloud-upload me-2"></i>Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px">
        <div class="modal-content" style="border:none; border-radius:16px; overflow:hidden">
            <div class="modal-header" style="border-bottom:1px solid #f0f0f0; padding:1.25rem 1.5rem">
                <h5 class="modal-title" id="deleteModalLabel"
                    style="font-family:'Cormorant Garamond',serif; font-size:1.2rem; font-weight:600; color:#c0392b">
                    <i class="bi bi-trash me-2"></i>Delete Confirmation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:1.5rem">
                <p style="color:#555; font-size:0.9rem; margin:0">
                    Are you sure you want to delete
                    <strong id="deleteFileName" style="color:#333"></strong>?
                    This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f0; padding:1rem 1.5rem; gap:0.5rem">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                    style="border-radius:8px; font-size:0.9rem; padding:0.5rem 1.25rem">
                    Cancel
                </button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        style="border-radius:8px; font-weight:600; font-size:0.9rem; padding:0.5rem 1.5rem">
                        Yes, Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ── File upload preview ────────────────────────────────────────────────
    const fileInput = document.getElementById('fileInput');
    const dropZone  = document.getElementById('dropZone');
    const fileList  = document.getElementById('fileList');

    fileInput.addEventListener('change', showFiles);

    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('dragging'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragging'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('dragging');
        fileInput.files = e.dataTransfer.files;
        showFiles();
    });

    function showFiles() {
        const files = Array.from(fileInput.files);
        fileList.innerHTML = files.map(f =>
            `<div style="font-size:0.78rem; color:#555; padding:4px 0; border-bottom:1px solid #f5f5f5">
                <i class="bi bi-${f.type.startsWith('video') ? 'camera-video' : 'image'} me-2" style="color:var(--brand-gold)"></i>${f.name}
                <span style="color:#aaa; float:right">${(f.size / 1024 / 1024).toFixed(1)} MB</span>
            </div>`
        ).join('');
    }

    // ── Delete confirmation modal ──────────────────────────────────────────
    function confirmDelete(actionUrl, fileName) {
        document.getElementById('deleteForm').action = actionUrl;
        document.getElementById('deleteFileName').textContent = fileName;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
@endpush