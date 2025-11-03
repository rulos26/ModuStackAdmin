<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    /**
     * Registrar un nuevo usuario
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        // Verificar si el registro está habilitado
        if (!config('auth.enable_registration', true)) {
            return response()->json([
                'status' => 'error',
                'message' => 'El registro de nuevos usuarios está deshabilitado.',
            ], 403);
        }

        try {
            // Crear el usuario
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Generar token
            $tokenName = config('auth.token_name', 'auth_token');
            $token = $user->createToken($tokenName)->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Usuario registrado exitosamente',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al registrar usuario',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Iniciar sesión y generar token
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            // Intentar autenticación
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Credenciales incorrectas',
                ], 401);
            }

            $user = Auth::user();

            // Revocar tokens anteriores (opcional - para permitir múltiples sesiones, comentar estas líneas)
            // $user->tokens()->delete();

            // Generar nuevo token
            $tokenName = config('auth.token_name', 'auth_token');
            $token = $user->createToken($tokenName)->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Login exitoso',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al iniciar sesión',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cerrar sesión (revocar token)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            // Revocar el token actual
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Sesión cerrada exitosamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al cerrar sesión',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtener perfil del usuario autenticado
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function profile(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            return response()->json([
                'status' => 'success',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener perfil',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

