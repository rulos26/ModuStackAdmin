<div class="card card-primary card-outline h-100">
    <div class="card-header">
        <h3 class="card-title">{{ __('Profile Information') }}</h3>
    </div>
    <div class="card-body">
        <p class="text-muted mb-4">
            {{ __("Update your account's profile information and email address.") }}
        </p>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="mb-3">
                <label for="name" class="form-label">{{ __('Name') }}</label>
                <input id="name"
                       type="text"
                       name="name"
                       value="{{ old('name', $user->name) }}"
                       required
                       autofocus
                       autocomplete="name"
                       class="form-control @error('name') is-invalid @enderror">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email', $user->email) }}"
                       required
                       autocomplete="username"
                       class="form-control @error('email') is-invalid @enderror">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                        <span>{{ __('Your email address is unverified.') }}</span>
                        <button type="submit" form="send-verification" class="btn btn-link p-0 ms-2 align-baseline">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success mt-3 mb-0" role="alert">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </div>
                    @endif
                @endif
            </div>

            <div class="d-flex align-items-center justify-content-between">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> {{ __('Save') }}
                </button>

                @if (session('status') === 'profile-updated')
                    <span class="text-success fw-semibold">{{ __('Saved.') }}</span>
                @endif
            </div>
        </form>
    </div>
</div>
