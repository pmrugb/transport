@extends('layouts.app', ['title' => 'Districts | Free Public Transport System', 'pageBadge' => 'Settings'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Settings</p>
            <h1 class="page-title">Districts</h1>
            <p class="page-subtitle">Create and manage district records linked to divisions.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-xl-5">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Add District</h3>
                    <p class="section-copy">Create a new district and assign its division.</p>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('settings.districts.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="division_id">Division</label>
                                <select class="form-select @error('division_id') is-invalid @enderror" id="division_id" name="division_id">
                                    <option value="">Select division</option>
                                    @foreach ($divisions as $division)
                                        <option value="{{ $division->id }}" @selected((string) old('division_id') === (string) $division->id)>{{ $division->name }}</option>
                                    @endforeach
                                </select>
                                @error('division_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="name">District Name</label>
                                <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Enter district name">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 d-flex gap-2">
                                <button class="btn btn-success" type="submit">Save District</button>
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
                            <h3 class="section-title">District Records</h3>
                            <p class="section-copy">All saved districts with linked division details.</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-shell table-wrap">
                        <table class="table table-app align-middle">
                            <thead>
                                <tr>
                                    <th>Sr #</th>
                                    <th>District</th>
                                    <th>Division</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($districts as $district)
                                    <tr>
                                        <td>{{ $districts->firstItem() + $loop->index }}</td>
                                        <td class="fw-semibold">{{ $district->name }}</td>
                                        <td>{{ $district->division_name }}</td>
                                        <td>{{ $district->created_at?->format('d-m-Y') }}</td>
                                        <td class="text-center text-nowrap">
                                            <div class="action-stack justify-content-center">
                                                <a href="{{ route('settings.districts.edit', $district) }}" class="action-btn btn-edit" title="Edit District">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <form method="post" action="{{ route('settings.districts.destroy', $district) }}" class="d-inline" data-confirm-delete data-delete-message="Are you sure you want to delete <strong>{{ e($district->name) }}</strong>?">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="action-btn btn-vacate border-0" title="Delete District">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">No districts found yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @include('settings.partials.pagination', ['paginator' => $districts, 'perPage' => $perPage])
                </div>
            </div>
        </div>
    </section>
@endsection
