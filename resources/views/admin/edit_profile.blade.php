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
        max-width: 600px; /* Thinner layout for profile editing */
        margin: 0 auto;
    }
    .form-body {
        padding: 3rem 2.5rem;
    }
    .profile-header-edit {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 2.5rem;
    }
    .avatar-edit {
        width: 110px;
        height: 110px;
        background-color: #F8F6F2;
        border: 2px dashed #D6D6D6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: var(--brand-gold-dark);
        margin-bottom: 0.75rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .avatar-edit:hover {
        background-color: #FDFBF7;
        border-color: var(--brand-gold);
        transform: scale(1.02);
    }
    .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #555;
        margin-bottom: 0.5rem;
        display: block;
    }
    .form-control {
        background-color: #FFFFFF;
        border: 1px solid #EBEBEB;
        border-radius: 10px;
        padding: 0.85rem 1.25rem;
        font-size: 0.95rem;
        color: var(--text-dark);
        transition: all 0.2s;
        width: 100%;
        display: block;
        margin-bottom: 1.5rem;
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
        border-radius: 50px;
        padding: 1rem;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s, transform 0.1s;
        width: 100%;
        margin-top: 1rem;
    }
    .btn-submit:hover {
        background-color: #8A642B;
    }
    .btn-submit:active {
        transform: scale(0.98);
    }
</style>
@endpush

@section('content')
    <h1 class="page-title">Edit <em>Profile</em></h1>

    <div class="form-card">
        <form action="{{ route('admin.edit_profile.submit') }}" method="POST">
            @csrf
            <div class="form-body">
                
                <div class="profile-header-edit">
                    <div class="avatar-edit" title="Change Avatar">
                        <i class="bi bi-camera"></i>
                    </div>
                    <div style="font-size: 0.85rem; color: #888; font-weight: 500;">Click to upload photo</div>
                </div>

                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" name="name" value="{{ session('admin_name', 'EGA MUTIARA') }}" required>

                <label class="form-label">Email Address</label>
                <input type="email" class="form-control" name="email" value="{{ session('admin_email', 'admin@gmail.com') }}" required>

                <label class="form-label">New Password (Leave blank to keep current)</label>
                <input type="password" class="form-control" name="password" placeholder="••••••••">

                <button type="submit" class="btn-submit">Update Profile</button>
            </div>
        </form>
    </div>
@endsection
