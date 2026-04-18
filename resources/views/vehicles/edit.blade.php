@extends('layouts.app', ['title' => 'Edit Vehicle | Free Public Transport System', 'pageBadge' => 'Vehicle Directory'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Vehicle Directory</p>
            <h1 class="page-title">Edit Vehicle</h1>
            <p class="page-subtitle">Update the selected vehicle record.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('vehicles.index') }}">Back to Vehicles</a>
            <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" class="d-inline" data-confirm-delete data-delete-message="Are you sure you want to delete <strong>{{ e($vehicle->registration_no) }}</strong>?">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete Vehicle</button>
            </form>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Vehicle Details</h3>
                    <p class="section-copy">Adjust vehicle information below.</p>
                </div>
                <div class="card-body">
                    @include('vehicles.partials.form')
                </div>
            </div>
        </div>
    </section>
@endsection
