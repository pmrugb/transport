@extends('layouts.app', ['title' => 'Divisions | Free Public Transport System', 'pageBadge' => 'Settings'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Settings</p>
            <h1 class="page-title">Divisions</h1>
            <p class="page-subtitle">Create and manage division records used across the transport system.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-xl-5">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Add Division</h3>
                    <p class="section-copy">Create a new division record.</p>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('settings.divisions.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="name">Division Name</label>
                                <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Enter division name">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 d-flex gap-2">
                                <button class="btn btn-success" type="submit">Save Division</button>
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
                            <h3 class="section-title">Division Records</h3>
                            <p class="section-copy">All saved divisions with linked district counts.</p>
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
                                    <th>Districts</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($divisions as $division)
                                    <tr>
                                        <td>{{ $divisions->firstItem() + $loop->index }}</td>
                                        <td class="fw-semibold">{{ $division->name }}</td>
                                        <td>{{ $division->districts_count }}</td>
                                        <td>{{ $division->created_at?->format('d-m-Y') }}</td>
                                        <td class="text-center text-nowrap">
                                            <div class="action-stack justify-content-center">
                                                <a href="{{ route('settings.divisions.edit', $division) }}" class="action-btn btn-edit" title="Edit Division">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <form method="post" action="{{ route('settings.divisions.destroy', $division) }}" class="d-inline" data-confirm-delete data-delete-message="Are you sure you want to delete <strong>{{ e($division->name) }}</strong>?">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="action-btn btn-vacate border-0" title="Delete Division">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">No divisions found yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @include('settings.partials.pagination', ['paginator' => $divisions, 'perPage' => $perPage])
                </div>
            </div>
        </div>
    </section>
@endsection
