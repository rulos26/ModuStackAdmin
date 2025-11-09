<x-guest-layout>
    <p class="login-box-msg">¿Olvidaste tu contraseña? Ingresa tu correo para enviarte un enlace de recuperación.</p>

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf

        <div class="input-group mb-3">
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror"
                placeholder="Correo electrónico"
                required
                autofocus
            >
            <span class="input-group-text">
                <i class="fas fa-envelope"></i>
            </span>
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-paper-plane me-1"></i> Enviar enlace
            </button>
        </div>
    </form>

    <p class="mt-3 mb-0 text-center">
        <a href="{{ route('login') }}" class="text-primary">Volver al inicio de sesión</a>
    </p>
</x-guest-layout>
