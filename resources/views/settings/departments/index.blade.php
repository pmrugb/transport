@extends('layouts.app', ['title' => 'Departments | Free Public Transport System', 'pageBadge' => 'Settings'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Settings</p>
            <h1 class="page-title">Departments</h1>
            <p class="page-subtitle">Create and manage department records used across the system.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-xl-5">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Add Department</h3>
                    <p class="section-copy">Create a new department record.</p>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('settings.departments.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="name">Department Name <span class="text-danger">*</span></label>
                                <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Enter department name">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="status">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                    @foreach ($statuses as $value => $label)
                                        <option value="{{ $value }}" @selected(old('status', 'active') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 d-flex gap-2">
                                <button class="btn btn-success" type="submit">Save Department</button>
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
                            <h3 class="section-title">Department Records</h3>
                            <p class="section-copy">All saved departments with status details.</p>
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
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($departments as $department)
                                    <tr>
                                        <td>{{ $departments->firstItem() + $loop->index }}</td>
                                        <td class="fw-semibold">{{ $department->name }}</td>
                                        <td>{{ $statuses[$department->status] ?? ucfirst($department->status) }}</td>
                                        <td>{{ $department->created_at?->format('d-m-Y') }}</td>
                                        <td class="text-center text-nowrap">
                                            <div class="action-stack justify-content-center">
                                                <a href="{{ route('settings.departments.edit', $department) }}" class="action-btn btn-edit" title="Edit Department">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <form method="post" action="{{ route('settings.departments.destroy', $department) }}" class="d-inline" data-confirm-delete data-delete-message="Are you sure you want to delete <strong>{{ e($department->name) }}</strong>?">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="action-btn btn-vacate border-0" title="Delete Department">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">No departments found yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @include('settings.partials.pagination', ['paginator' => $departments, 'perPage' => $perPage])
                </div>
            </div>
        </div>
    </section>
@endsection
