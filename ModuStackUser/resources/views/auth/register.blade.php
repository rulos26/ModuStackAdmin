<x-guest-layout>
    <p class="login-box-msg">Regístrate para crear tu cuenta</p>

    <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf

        <div class="input-group mb-3">
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                class="form-control @error('name') is-invalid @enderror"
                placeholder="Nombre completo"
                required
                autofocus
                autocomplete="name"
            >
            <span class="input-group-text">
                <i class="fas fa-user"></i>
            </span>
            @error('name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror"
                placeholder="Correo electrónico"
                required
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
                <i class="fas fa-user-plus me-1"></i> Crear cuenta
            </button>
        </div>
    </form>

    <p class="mt-3 mb-0 text-center">
        <span>¿Ya tienes una cuenta?</span>
        <a href="{{ route('login') }}" class="text-primary">Inicia sesión</a>
    </p>
</x-guest-layout>
