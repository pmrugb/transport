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
