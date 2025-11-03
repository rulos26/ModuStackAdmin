<?php

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Obtener lista de usuarios
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = User::select('id', 'name', 'email', 'created_at')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $users,
            'count' => $users->count(),
        ], 200);
    }

    /**
     * Obtener usuario por ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $user = User::select('id', 'name', 'email', 'email_verified_at', 'created_at', 'updated_at')
            ->find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Usuario no encontrado',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $user,
        ], 200);
    }
}

