@php
    $currentPage = $paginator->currentPage();
    $lastPage = $paginator->lastPage();
    $windowStart = max(1, $currentPage - 1);
    $windowEnd = min($lastPage, $currentPage + 1);

    if ($windowStart <= 2) {
        $windowEnd = min($lastPage, 3);
    }

    if ($windowEnd >= $lastPage - 1) {
        $windowStart = max(1, $lastPage - 2);
    }
@endphp

<div class="table-pagination-bar">
    <div class="table-pagination-summary">
        Showing {{ $paginator->firstItem() ?? 0 }} to {{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }} entries
    </div>

    <form method="get" class="table-per-page-form">
        <select class="form-select table-per-page-select" name="per_page" onchange="this.form.submit()">
            @foreach ([10, 25, 50, 100, 'all'] as $option)
                <option value="{{ $option }}" @selected((string) $perPage === (string) $option)>{{ $option === 'all' ? 'All' : $option }}</option>
            @endforeach
        </select>
        <span class="table-per-page-label">per page</span>
    </form>

    @if ($paginator->hasPages())
        <nav aria-label="Pagination">
            <ul class="pagination table-pagination-list mb-0">
                <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() ?? '#' }}" aria-label="Previous">
                        <i class="fa-solid fa-chevron-left app-icon"></i>
                    </a>
                </li>

                @if ($windowStart > 1)
                    <li class="page-item {{ $currentPage === 1 ? 'active' : '' }}">
                        <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
                    </li>
                @endif

                @if ($windowStart > 2)
                    <li class="page-item disabled">
                        <span class="page-link table-pagination-ellipsis">...</span>
                    </li>
                @endif

                @for ($page = $windowStart; $page <= $windowEnd; $page++)
                    <li class="page-item {{ $currentPage === $page ? 'active' : '' }}">
                        <a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                    </li>
                @endfor

                @if ($windowEnd < $lastPage - 1)
                    <li class="page-item disabled">
                        <span class="page-link table-pagination-ellipsis">...</span>
                    </li>
                @endif

                @if ($windowEnd < $lastPage)
                    <li class="page-item {{ $currentPage === $lastPage ? 'active' : '' }}">
                        <a class="page-link" href="{{ $paginator->url($lastPage) }}">{{ $lastPage }}</a>
                    </li>
                @endif

                <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() ?? '#' }}" aria-label="Next">
                        <i class="fa-solid fa-chevron-right app-icon"></i>
                    </a>
                </li>
            </ul>
        </nav>
    @endif

    <div class="table-pagination-controls">
    </div>
</div>
