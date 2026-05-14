<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="deleteConfirmModalMessage">
                Are you sure you want to delete this record?
            </div>
            <div class="px-3 px-md-4 pb-2 d-none" id="deleteConfirmModalMonthsWrap">
                <style>
                    #deleteConfirmModalMonthsWrap .dropdown-toggle {
                        min-height: 42px;
                        border-radius: 0.8rem;
                        border: 1px solid #dbe4ec;
                        background: #fff;
                        color: #1f2f43;
                        font-size: 0.84rem;
                        font-weight: 600;
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        padding: 0.55rem 0.8rem;
                    }

                    #deleteConfirmModalMonthsWrap .dropdown-menu {
                        width: 100%;
                        max-height: 210px;
                        overflow-y: auto;
                        border-radius: 0.85rem;
                        border: 1px solid #dbe4ec;
                        box-shadow: 0 18px 42px rgba(31, 47, 67, 0.14);
                        padding: 0.3rem;
                    }

                    #deleteConfirmModalMonthsWrap .dropdown-item {
                        border-radius: 0.7rem;
                        padding: 0.55rem 0.7rem;
                        font-size: 0.84rem;
                        font-weight: 600;
                    }

                    #deleteConfirmModalMonthsWrap .dropdown-item.active,
                    #deleteConfirmModalMonthsWrap .dropdown-item:active {
                        background: #2d6948;
                        color: #fff;
                    }
                </style>
                <label class="form-label fw-semibold" for="deleteConfirmModalMonthsButton">Keep Recent Months</label>
                <input type="hidden" id="deleteConfirmModalMonths" value="1">
                <div class="dropdown">
                    <button class="btn dropdown-toggle w-100" id="deleteConfirmModalMonthsButton" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span id="deleteConfirmModalMonthsLabel">1 month</span>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="deleteConfirmModalMonthsButton">
                        @for ($month = 1; $month <= 12; $month++)
                            <button class="dropdown-item {{ $month === 1 ? 'active' : '' }}" type="button" data-delete-month-value="{{ $month }}">
                                {{ $month }} {{ $month === 1 ? 'month' : 'months' }}
                            </button>
                        @endfor
                    </div>
                </div>
                <div class="form-text">Older records before this retention window will be deleted.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="deleteConfirmModalSubmit">Delete</button>
            </div>
        </div>
    </div>
</div>

@php($deleteBlocked = session('delete_blocked'))

@if (is_array($deleteBlocked) && ($deleteBlocked['entity'] ?? null) === 'transporter')
    <div class="modal fade" id="deleteBlockedModal" tabindex="-1" aria-labelledby="deleteBlockedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteBlockedModalLabel">Unable to Delete Transporter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if (! empty($deleteBlocked['vehicles']) && is_array($deleteBlocked['vehicles']))
                        <p class="mb-3">
                            {{ $deleteBlocked['message'] ?? 'This transporter cannot be deleted right now.' }}
                            {{ $deleteBlocked['guidance'] ?? '' }}
                        </p>
                        <div class="mb-0">
                            <strong class="d-block mb-2">{{ (int) ($deleteBlocked['vehicle_count'] ?? 0) === 1 ? 'Attached vehicle:' : 'Attached vehicles:' }}</strong>
                            <div class="d-flex flex-column gap-2">
                                @foreach ($deleteBlocked['vehicles'] as $vehicle)
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <span>{{ $vehicle['registration_no'] ?? 'N/A' }}</span>
                                        @if (! empty($vehicle['id']))
                                            <a href="{{ route('vehicles.edit', $vehicle['id']) }}" class="btn btn-outline-secondary btn-sm small">Edit Vehicle</a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="mb-0">{{ $deleteBlocked['message'] ?? 'This transporter cannot be deleted right now.' }}</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('vehicles.index') }}" class="btn btn-danger">Manage Vehicles</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteBlockedModalElement = document.getElementById('deleteBlockedModal');

            if (!deleteBlockedModalElement || !window.bootstrap || !window.bootstrap.Modal) {
                return;
            }

            window.bootstrap.Modal.getOrCreateInstance(deleteBlockedModalElement).show();
        });
    </script>
@endif
