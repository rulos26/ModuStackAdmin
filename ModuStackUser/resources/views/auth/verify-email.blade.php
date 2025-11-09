<x-guest-layout>
    <p class="login-box-msg">
        Gracias por registrarte. Antes de continuar, verifica tu correo electrónico desde el enlace que te enviamos.
    </p>

    @if (session('status') === 'verification-link-sent')
        <div class="alert alert-success" role="alert">
            Hemos enviado un nuevo enlace de verificación a tu correo electrónico.
        </div>
    @endif

    <div class="d-flex flex-column gap-2">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-paper-plane me-1"></i> Reenviar enlace de verificación
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary btn-block">
                <i class="fas fa-sign-out-alt me-1"></i> Cerrar sesión
            </button>
        </form>
    </div>
</x-guest-layout>
