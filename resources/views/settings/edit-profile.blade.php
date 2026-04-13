@extends('layouts.app', ['title' => 'Edit Profile | Free Public Transport System', 'pageBadge' => 'Settings'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Settings</p>
            <h1 class="page-title">Edit Profile</h1>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12 col-xl-12 col-lg-12">
            <div class="card section-card profile-card">
                <div class="card-body">
                    <form method="post" action="{{ route('settings.profile.update') }}" class="profile-form">
                        @csrf
                        @method('put')

                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label profile-label" for="name">Name <span class="text-danger">*</span></label>
                                <input class="form-control profile-input @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name', auth()->user()->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label profile-label" for="email">Email</label>
                                <input class="form-control profile-input" id="email" type="email" value="{{ auth()->user()->email }}" disabled>
                                <p class="profile-help mb-0">Email cannot be changed.</p>
                            </div>

                            <div class="col-12">
                                <label class="form-label profile-label" for="password">New Password</label>
                                <input class="form-control profile-input @error('password') is-invalid @enderror" id="password" name="password" type="password" placeholder="Leave blank to keep current">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label profile-label" for="password_confirmation">Confirm New Password</label>
                                <input class="form-control profile-input" id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm new password">
                            </div>

                            <div class="col-12">
                                <button class="btn btn-success profile-save-btn" type="submit">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
