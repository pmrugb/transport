@extends('layouts.app', ['title' => 'Edit Division | Free Public Transport System', 'pageBadge' => 'Settings'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Settings</p>
            <h1 class="page-title">Edit Division</h1>
            <p class="page-subtitle">Update the selected division record.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12 col-xl-7">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Division Details</h3>
                    <p class="section-copy">Edit division information below.</p>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('settings.divisions.update', $division) }}">
                        @csrf
                        @method('put')
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="name">Division Name</label>
                                <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name', $division->name) }}">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 d-flex gap-2">
                                <button class="btn btn-success" type="submit">Save Changes</button>
                                <a class="btn btn-outline-secondary" href="{{ route('settings.divisions.index') }}">Back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
