<div class="card card-danger card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ __('Delete Account') }}</h3>
    </div>
    <div class="card-body">
        <p class="text-muted">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>

        <button type="button" class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#confirm-user-deletion">
            <i class="fas fa-user-slash me-1"></i> {{ __('Delete Account') }}
        </button>
    </div>
</div>

<div class="modal fade" id="confirm-user-deletion" tabindex="-1" aria-labelledby="confirm-user-deletion-label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirm-user-deletion-label">{{ __('Delete Account') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                <div class="modal-body">
                    <p class="mb-3">
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                    </p>

                    <div class="mb-3">
                        <label for="delete_password" class="form-label">{{ __('Password') }}</label>
                        <input id="delete_password"
                               name="password"
                               type="password"
                               class="form-control @if($errors->userDeletion->has('password')) is-invalid @endif"
                               placeholder="{{ __('Password') }}"
                               required>
                        @if($errors->userDeletion->has('password'))
                            <div class="invalid-feedback">
                                {{ $errors->userDeletion->first('password') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-1"></i> {{ __('Delete Account') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if ($errors->userDeletion->isNotEmpty())
            const modalElement = document.getElementById('confirm-user-deletion');
            const deleteModal = new bootstrap.Modal(modalElement);
            deleteModal.show();
        @endif
    });
</script>
@endpush
