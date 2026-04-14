@extends('layouts.app', ['title' => 'All Routes | Free Public Transport System', 'pageBadge' => 'Route Directory'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Route Directory</p>
            <h1 class="page-title">All Routes</h1>
            <p class="page-subtitle">Review all route records with district coverage, corridor points, and distance.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-sm-6 col-xl-6"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Total Routes</p><h2 class="stat-value">{{ $stats['total'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-road app-icon"></i></span></div><p class="stat-note">All routes currently available in the system.</p></div></div></div>
        <div class="col-sm-6 col-xl-6"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Covered Districts</p><h2 class="stat-value">{{ $stats['districts'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-map-location-dot app-icon"></i></span></div><p class="stat-note">Districts currently linked with route records.</p></div></div></div>
    </section>

    <section class="card section-card table-card mb-4">
        <div class="card-header">
            <div class="table-toolbar">
                <div>
                    <h3 class="section-title">Route Records</h3>
                    <p class="section-copy">Complete listing of company and private route records.</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-shell table-wrap">
                <table class="table table-app table-routes align-middle">
                    <thead>
                        <tr>
                            <th>Sr #</th>
                            <th>Route Code</th>
                            <th>Route Name</th>
                            <th>Starting Point</th>
                            <th>Ending Point</th>
                            <th>Timing</th>
                            <th>Total Distance</th>
                            <th>District</th>
                            @if ($canManageRoutes)
                                <th class="text-center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($routes as $route)
                            <tr>
                                <td class="text-nowrap">{{ $routes->firstItem() + $loop->index }}</td>
                                <td class="text-nowrap">{{ $route->route_code }}</td>
                                <td class="fw-semibold text-nowrap">{{ $route->route_name }}</td>
                                <td class="text-nowrap">{{ $route->starting_point }}</td>
                                <td>{{ $route->ending_point }}</td>
                                <td class="text-nowrap">{{ $route->timing }}</td>
                                <td class="text-nowrap">{{ $route->total_distance }} km</td>
                                <td class="text-nowrap">{{ $route->district?->name }}</td>
                                @if ($canManageRoutes)
                                    <td class="text-center text-nowrap">
                                        <div class="action-stack justify-content-center">
                                            <a href="{{ route('routes.show', $route) }}" class="action-btn btn-view" title="View Route">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('routes.edit', $route) }}" class="action-btn btn-edit" title="Edit Route">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <form action="{{ route('routes.destroy', $route) }}" method="POST" class="d-inline" data-confirm-delete data-delete-message="Are you sure you want to delete <strong>{{ e($route->route_name) }}</strong>?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn btn-vacate border-0" title="Delete Route">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr><td colspan="{{ $canManageRoutes ? 10 : 9 }}" class="text-center text-muted py-4">No routes found yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @include('settings.partials.pagination', ['paginator' => $routes, 'perPage' => $perPage])
        </div>
    </section>
@endsection
