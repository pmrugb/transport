@extends('layouts.app', ['title' => 'Edit Vehicle Type | Free Public Transport System', 'pageBadge' => 'Vehicle Directory'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Vehicle Directory</p>
            <h1 class="page-title">Edit Vehicle Type</h1>
            <p class="page-subtitle">Update the selected vehicle type record.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12 col-xl-7">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Vehicle Type Details</h3>
                    <p class="section-copy">Edit vehicle type information below.</p>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('vehicles.types.update', $vehicleType) }}">
                        @csrf
                        @method('put')
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="name">Name <span class="text-danger">*</span></label>
                                <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name', $vehicleType->name) }}">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="description">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $vehicleType->description) }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="seating_capacity">Seating Capacity</label>
                                <input class="form-control @error('seating_capacity') is-invalid @enderror" id="seating_capacity" name="seating_capacity" type="number" min="1" value="{{ old('seating_capacity', $vehicleType->seating_capacity) }}">
                                @error('seating_capacity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="status">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                    @foreach ($statuses as $value => $label)
                                        <option value="{{ $value }}" @selected(old('status', $vehicleType->status) === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 d-flex gap-2">
                                <button class="btn btn-success" type="submit">Save Changes</button>
                                <a class="btn btn-outline-secondary" href="{{ route('vehicles.types.index') }}">Back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
