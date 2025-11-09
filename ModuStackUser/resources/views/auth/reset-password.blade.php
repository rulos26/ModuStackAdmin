<x-guest-layout>
    <p class="login-box-msg">Ingresa una nueva contraseña para tu cuenta.</p>

    <form method="POST" action="{{ route('password.store') }}" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="input-group mb-3">
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email', $request->email) }}"
                class="form-control @error('email') is-invalid @enderror"
                placeholder="Correo electrónico"
                required
                autofocus
                autocomplete="username"
            >
            <span class="input-group-text">
                <i class="fas fa-envelope"></i>
            </span>
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input
                type="password"
                id="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Nueva contraseña"
                required
                autocomplete="new-password"
            >
            <span class="input-group-text">
                <i class="fas fa-lock"></i>
            </span>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="input-group mb-4">
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                placeholder="Confirmar contraseña"
                required
                autocomplete="new-password"
            >
            <span class="input-group-text">
                <i class="fas fa-lock"></i>
            </span>
            @error('password_confirmation')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-sync-alt me-1"></i> Restablecer contraseña
            </button>
        </div>
    </form>

    <p class="mt-3 mb-0 text-center">
        <a href="{{ route('login') }}" class="text-primary">Volver al inicio de sesión</a>
    </p>
</x-guest-layout>
