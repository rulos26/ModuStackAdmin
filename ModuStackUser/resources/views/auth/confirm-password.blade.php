<x-guest-layout>
    <p class="login-box-msg">
        Esta sección es segura. Confirma tu contraseña para continuar.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}" novalidate>
        @csrf

        <div class="input-group mb-4">
            <input
                type="password"
                id="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Contraseña"
                required
                autocomplete="current-password"
            >
            <span class="input-group-text">
                <i class="fas fa-lock"></i>
            </span>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-check me-1"></i> Confirmar
            </button>
        </div>
    </form>

    <p class="mt-3 mb-0 text-center">
        <a href="{{ route('password.request') }}" class="text-primary">¿Olvidaste tu contraseña?</a>
    </p>
</x-guest-layout>
