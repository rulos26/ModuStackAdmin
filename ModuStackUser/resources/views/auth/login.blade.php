<x-guest-layout>
    <p class="login-box-msg">Inicia sesión para comenzar tu sesión</p>

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" novalidate>
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

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                <label class="form-check-label" for="remember_me">Recordarme</label>
            </div>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
            @endif
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-sign-in-alt me-1"></i> Ingresar
            </button>
        </div>
    </form>

    @if (Route::has('register'))
        <p class="mt-3 mb-0 text-center">
            <span>¿No tienes una cuenta?</span>
            <a href="{{ route('register') }}" class="text-primary">Regístrate</a>
        </p>
    @endif
</x-guest-layout>
