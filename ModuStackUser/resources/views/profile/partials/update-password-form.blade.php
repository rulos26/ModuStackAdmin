<div class="card card-secondary card-outline h-100">
    <div class="card-header">
        <h3 class="card-title">{{ __('Update Password') }}</h3>
    </div>
    <div class="card-body">
        <p class="text-muted mb-4">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="mb-3">
                <label for="update_password_current_password" class="form-label">{{ __('Current Password') }}</label>
                <input id="update_password_current_password"
                       type="password"
                       name="current_password"
                       autocomplete="current-password"
                       class="form-control @if($errors->updatePassword->has('current_password')) is-invalid @endif">
                @if($errors->updatePassword->has('current_password'))
                    <div class="invalid-feedback">
                        {{ $errors->updatePassword->first('current_password') }}
                    </div>
                @endif
            </div>

            <div class="mb-3">
                <label for="update_password_password" class="form-label">{{ __('New Password') }}</label>
                <input id="update_password_password"
                       type="password"
                       name="password"
                       autocomplete="new-password"
                       class="form-control @if($errors->updatePassword->has('password')) is-invalid @endif">
                @if($errors->updatePassword->has('password'))
                    <div class="invalid-feedback">
                        {{ $errors->updatePassword->first('password') }}
                    </div>
                @endif
            </div>

            <div class="mb-3">
                <label for="update_password_password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                <input id="update_password_password_confirmation"
                       type="password"
                       name="password_confirmation"
                       autocomplete="new-password"
                       class="form-control @if($errors->updatePassword->has('password_confirmation')) is-invalid @endif">
                @if($errors->updatePassword->has('password_confirmation'))
                    <div class="invalid-feedback">
                        {{ $errors->updatePassword->first('password_confirmation') }}
                    </div>
                @endif
            </div>

            <div class="d-flex align-items-center justify-content-between">
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-key me-1"></i> {{ __('Save') }}
                </button>

                @if (session('status') === 'password-updated')
                    <span class="text-success fw-semibold">{{ __('Saved.') }}</span>
                @endif
            </div>
        </form>
    </div>
</div>
