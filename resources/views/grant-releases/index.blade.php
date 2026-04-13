@extends('layouts.app', ['title' => 'All Grant Releases | Free Public Transport System', 'pageBadge' => 'Grant Release Directory'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Grant Release Directory</p>
            <h1 class="page-title">All Grant Releases</h1>
            <p class="page-subtitle">Review installment releases, dates, installment numbers, and released amounts.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Total Releases</p><h2 class="stat-value">{{ $stats['total'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-hand-holding-dollar app-icon"></i></span></div><p class="stat-note">All grant installment releases in the system.</p></div></div></div>
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Covered Grants</p><h2 class="stat-value">{{ $stats['grants'] }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-sack-dollar app-icon"></i></span></div><p class="stat-note">Grants currently linked with release records.</p></div></div></div>
        <div class="col-sm-6 col-xl-4"><div class="card stat-card"><div class="card-body"><div class="stat-card-head"><div><p class="stat-label">Released Amount</p><h2 class="stat-value">{{ number_format((float) $stats['released'], 2) }}</h2></div><span class="stat-card-icon"><i class="fa-solid fa-money-bill-transfer app-icon"></i></span></div><p class="stat-note">Total amount released across all installments.</p></div></div></div>
    </section>

    <section class="card section-card table-card mb-4">
        <div class="card-header">
            <div class="table-toolbar">
                <div>
                    <h3 class="section-title">Grant Release Records</h3>
                    <p class="section-copy">Complete listing of installment release records.</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-shell table-wrap">
                <table class="table table-app table-routes align-middle">
                    <thead>
                        <tr>
                            <th>Sr #</th>
                            <th>Grant</th>
                            <th>Release Amount</th>
                            <th>Release Date</th>
                            <th>Installment No</th>
                            <th>Released By</th>
                            <th>Remarks</th>
                            @if ($canManageGrantReleases)
                                <th class="text-center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($grantReleases as $grantRelease)
                            <tr>
                                <td class="text-nowrap">{{ $grantReleases->firstItem() + $loop->index }}</td>
                                <td class="fw-semibold">{{ $grantRelease->grant?->title ?: 'N/A' }}</td>
                                <td class="text-nowrap">{{ number_format((float) $grantRelease->release_amount, 2) }}</td>
                                <td class="text-nowrap">{{ $grantRelease->release_date?->format('Y-m-d') ?: 'N/A' }}</td>
                                <td class="text-nowrap">{{ $grantRelease->installment_no }}</td>
                                <td class="text-nowrap">{{ $grantRelease->released_by ?: 'N/A' }}</td>
                                <td>{{ $grantRelease->remarks ?: 'N/A' }}</td>
                                @if ($canManageGrantReleases)
                                    <td class="text-center text-nowrap">
                                        <div class="action-stack justify-content-center">
                                            <a href="{{ route('grant-releases.show', $grantRelease) }}" class="action-btn btn-view" title="View Grant Release"><i class="fa-solid fa-eye"></i></a>
                                            <a href="{{ route('grant-releases.edit', $grantRelease) }}" class="action-btn btn-edit" title="Edit Grant Release"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <form action="{{ route('grant-releases.destroy', $grantRelease) }}" method="POST" class="d-inline" data-confirm-delete data-delete-message="Are you sure you want to delete installment <strong>#{{ e((string) $grantRelease->installment_no) }}</strong>?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn btn-vacate border-0" title="Delete Grant Release"><i class="fa-solid fa-trash-can"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr><td colspan="{{ $canManageGrantReleases ? 8 : 7 }}" class="text-center text-muted py-4">No grant releases found yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @include('settings.partials.pagination', ['paginator' => $grantReleases, 'perPage' => $perPage])
        </div>
    </section>
@endsection
