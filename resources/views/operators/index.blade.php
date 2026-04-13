@extends('layouts.app', ['title' => 'All Transporters | Free Public Transport System', 'pageBadge' => 'Transporter Directory'])

@php use App\Models\Operator; @endphp

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Transporter Directory</p>
            <h1 class="page-title">All Transporters</h1>
            <p class="page-subtitle">Review all registered transporters, their owner type, district, and contact details.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Total Transporters</p><h2 class="stat-value">{{ $stats['total'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-users app-icon"></i></span></div><p class="stat-note">All transporters currently registered in the system.</p></div></div></div>
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Private Owners</p><h2 class="stat-value">{{ $stats['private'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-user app-icon"></i></span></div><p class="stat-note">Private transporters currently available for route assignment.</p></div></div></div>
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Covered Districts</p><h2 class="stat-value">{{ $stats['districts'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-map-location-dot app-icon"></i></span></div><p class="stat-note">Districts currently linked with transporter records.</p></div></div></div>

    </section>

    <section class="card section-card table-card mb-4">
        <div class="card-header">
            <div class="table-toolbar">
                <div>
                    <h3 class="section-title">Transporter Records</h3>
                    <p class="section-copy">Complete listing of company and private transporter records.</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-shell table-wrap">
                <table class="table table-app table-routes align-middle">
                    <thead>
                        <tr>
                            <th>Sr #</th>
                            <th>Owner Type</th>
                            <th>Name</th>
                            <th>CNIC</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>District</th>
                            @if ($canManageTransporters)
                                <th class="text-center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($operators as $operator)
                            <tr>
                                <td class="text-nowrap">{{ $operators->firstItem() + $loop->index }}</td>
                                <td class="text-nowrap">{{ $ownerTypes[$operator->owner_type] ?? ucfirst((string) $operator->owner_type) }}</td>
                                <td class="fw-semibold text-nowrap">{{ $operator->name }}</td>
                                <td class="text-nowrap">{{ $operator->cnic }}</td>
                                <td class="text-nowrap">{{ $operator->phone }}</td>
                                <td>{{ $operator->address }}</td>
                                <td class="text-nowrap">{{ $operator->district?->name }}</td>
                                @if ($canManageTransporters)
                                    <td class="text-center text-nowrap">
                                        <div class="action-stack justify-content-center">
                                            <a href="{{ route('transporters.show', $operator) }}" class="action-btn btn-view" title="View Transporter">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('transporters.edit', $operator) }}" class="action-btn btn-edit" title="Edit Transporter">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <form action="{{ route('transporters.destroy', $operator) }}" method="POST" class="d-inline" data-confirm-delete data-delete-message="Are you sure you want to delete <strong>{{ e($operator->name) }}</strong>?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn btn-vacate border-0" title="Delete Transporter">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr><td colspan="{{ $canManageTransporters ? 8 : 7 }}" class="text-center text-muted py-4">No transporters found yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-pagination-bar">
                <div class="table-pagination-summary">
                    Showing {{ $operators->firstItem() ?? 0 }} to {{ $operators->lastItem() ?? 0 }} of {{ $operators->total() }} entries
                </div>

                <div class="table-pagination-controls">
                    <form method="get" class="table-per-page-form">
                        <select class="form-select table-per-page-select" name="per_page" onchange="this.form.submit()">
                            @foreach ([10, 25, 50, 100] as $option)
                                <option value="{{ $option }}" @selected($perPage === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                        <span class="table-per-page-label">per page</span>
                    </form>

                    @if ($operators->hasPages())
                        <nav aria-label="Transporters pagination">
                            <ul class="pagination table-pagination-list mb-0">
                                <li class="page-item {{ $operators->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $operators->previousPageUrl() ?? '#' }}" aria-label="Previous">
                                        <i class="fa-solid fa-chevron-left app-icon"></i>
                                    </a>
                                </li>

                                @foreach ($operators->linkCollection() as $link)
                                    @continue($link['label'] === '&laquo; Previous' || $link['label'] === 'Next &raquo;')

                                    <li class="page-item {{ $link['active'] ? 'active' : '' }} {{ $link['url'] ? '' : 'disabled' }}">
                                        <a class="page-link" href="{{ $link['url'] ?? '#' }}">
                                            {{ str_replace('&hellip;', '...', $link['label']) }}
                                        </a>
                                    </li>
                                @endforeach

                                <li class="page-item {{ $operators->hasMorePages() ? '' : 'disabled' }}">
                                    <a class="page-link" href="{{ $operators->nextPageUrl() ?? '#' }}" aria-label="Next">
                                        <i class="fa-solid fa-chevron-right app-icon"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
