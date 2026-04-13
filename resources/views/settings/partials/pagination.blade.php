<div class="table-pagination-bar">
    <div class="table-pagination-summary">
        Showing {{ $paginator->firstItem() ?? 0 }} to {{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }} entries
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

        @if ($paginator->hasPages())
            <nav aria-label="Pagination">
                <ul class="pagination table-pagination-list mb-0">
                    <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() ?? '#' }}" aria-label="Previous">
                            <i class="fa-solid fa-chevron-left app-icon"></i>
                        </a>
                    </li>

                    @foreach ($paginator->linkCollection() as $link)
                        @continue($link['label'] === '&laquo; Previous' || $link['label'] === 'Next &raquo;')

                        <li class="page-item {{ $link['active'] ? 'active' : '' }} {{ $link['url'] ? '' : 'disabled' }}">
                            <a class="page-link" href="{{ $link['url'] ?? '#' }}">
                                {{ str_replace('&hellip;', '...', $link['label']) }}
                            </a>
                        </li>
                    @endforeach

                    <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() ?? '#' }}" aria-label="Next">
                            <i class="fa-solid fa-chevron-right app-icon"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        @endif
    </div>
</div>
