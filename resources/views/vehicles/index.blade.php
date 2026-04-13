@extends('layouts.app', ['title' => 'All Vehicles | Free Public Transport System', 'pageBadge' => 'Vehicle Directory'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Vehicle Directory</p>
            <h1 class="page-title">All Vehicles</h1>
            <p class="page-subtitle">Review registered vehicles, linked transporters, routes, and current operating status.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card table-card mb-4">
                <div class="card-header">
                    <div class="table-toolbar">
                        <div>
                            <h3 class="section-title">Vehicle Records</h3>
                            <p class="section-copy">Complete listing of vehicles available in the system.</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-shell table-wrap">
                        <table class="table table-app align-middle">
                            <thead>
                                <tr>
                                    <th>Sr #</th>
                                    <th>Transporter</th>
                                    <th>Vehicle Type</th>
                                    <th>Registration No</th>
                                    <th>Chassis No</th>
                                    <th>Route</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                    @if ($canManageVehicles)
                                        <th class="text-center">Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($vehicles as $vehicle)
                                    <tr>
                                        <td>{{ $vehicles->firstItem() + $loop->index }}</td>
                                        <td class="fw-semibold">{{ $vehicle->transporter?->name ?: 'N/A' }}</td>
                                        <td>{{ $vehicle->vehicleType?->name ?: 'N/A' }}</td>
                                        <td>{{ $vehicle->registration_no }}</td>
                                        <td>{{ $vehicle->chassis_no }}</td>
                                        <td>{{ $vehicle->route?->route_name ?: 'N/A' }}</td>
                                        <td class="text-capitalize">{{ $statuses[$vehicle->status] ?? ucfirst($vehicle->status) }}</td>
                                        <td>{{ $vehicle->remarks ?: 'N/A' }}</td>
                                        @if ($canManageVehicles)
                                            <td class="text-center text-nowrap">
                                                <div class="action-stack justify-content-center">
                                                    <a href="{{ route('vehicles.show', $vehicle) }}" class="action-btn btn-view" title="View Vehicle">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('vehicles.edit', $vehicle) }}" class="action-btn btn-edit" title="Edit Vehicle">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </a>
                                                    <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" class="d-inline" data-confirm-delete data-delete-message="Are you sure you want to delete <strong>{{ e($vehicle->registration_no) }}</strong>?">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="action-btn btn-vacate border-0" title="Delete Vehicle">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr><td colspan="{{ $canManageVehicles ? 9 : 8 }}" class="text-center text-muted py-4">No vehicles found yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @include('settings.partials.pagination', ['paginator' => $vehicles, 'perPage' => $perPage])
                </div>
            </div>
        </div>
    </section>
@endsection
