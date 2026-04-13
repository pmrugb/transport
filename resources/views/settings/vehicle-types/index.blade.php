@extends('layouts.app', ['title' => 'Vehicle Types | Free Public Transport System', 'pageBadge' => 'Vehicle Directory'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Vehicle Directory</p>
            <h1 class="page-title">Vehicle Types</h1>
            <p class="page-subtitle">Create and manage vehicle type records used across the transport system.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-xl-5">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Add Vehicle Type</h3>
                    <p class="section-copy">Create a new vehicle type record.</p>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('vehicles.types.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="name">Name <span class="text-danger">*</span></label>
                                <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Enter vehicle type name">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="description">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Enter vehicle type description">{{ old('description') }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="seating_capacity">Seating Capacity</label>
                                <input class="form-control @error('seating_capacity') is-invalid @enderror" id="seating_capacity" name="seating_capacity" type="number" min="1" value="{{ old('seating_capacity') }}" placeholder="e.g. 40">
                                @error('seating_capacity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 d-flex gap-2">
                                <button class="btn btn-success" type="submit">Save Vehicle Type</button>
                                <button class="btn btn-outline-secondary" type="reset">Clear</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-7">
            <div class="card section-card table-card">
                <div class="card-header">
                    <div class="table-toolbar">
                        <div>
                            <h3 class="section-title">Vehicle Type Records</h3>
                            <p class="section-copy">All saved vehicle types with seating capacity and status.</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-shell table-wrap">
                        <table class="table table-app align-middle">
                            <thead>
                                <tr>
                                    <th>Sr #</th>
                                    <th>Name</th>
                                    <th>Seating Capacity</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($vehicleTypes as $vehicleType)
                                    <tr>
                                        <td>{{ $vehicleTypes->firstItem() + $loop->index }}</td>
                                        <td class="fw-semibold">{{ $vehicleType->name }}</td>
                                        <td>{{ $vehicleType->seating_capacity ?: 'N/A' }}</td>
                                        <td>{{ $vehicleType->description ?: 'N/A' }}</td>
                                        <td>
                                            <div class="table-action-group">
                                                <a class="btn btn-sm table-inline-btn" href="{{ route('vehicles.types.edit', $vehicleType) }}">Edit</a>
                                                <form method="post" action="{{ route('vehicles.types.destroy', $vehicleType) }}" data-confirm-delete data-delete-message="Are you sure you want to delete <strong>{{ e($vehicleType->name) }}</strong>?">
                                                    @csrf
                                                    @method('delete')
                                                    <button class="btn btn-sm table-inline-btn table-inline-btn-danger" type="submit">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">No vehicle types found yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @include('settings.partials.pagination', ['paginator' => $vehicleTypes, 'perPage' => $perPage])
                </div>
            </div>
        </div>
    </section>
@endsection
