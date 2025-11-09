<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;

class ActivityLogService
{
    /**
     * Log user activity.
     */
    public function log(
        User $user,
        string $action,
        ?string $modelType = null,
        ?int $modelId = null,
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?Request $request = null
    ): UserActivityLog {
        $request = $request ?? request();

        return UserActivityLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
        ]);
    }

    /**
     * Get user activity logs.
     */
    public function getUserLogs(User $user, int $limit = 50)
    {
        return UserActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get activity logs for a specific model.
     */
    public function getModelLogs(string $modelType, int $modelId, int $limit = 50)
    {
        return UserActivityLog::where('model_type', $modelType)
            ->where('model_id', $modelId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Export logs to array format for CSV/Excel.
     */
    public function exportLogs(array $logs): array
    {
        return $logs->map(function ($log) {
            return [
                'ID' => $log->id,
                'Usuario' => $log->user->name ?? 'N/A',
                'Email' => $log->user->email ?? 'N/A',
                'Acción' => $log->action,
                'Descripción' => $log->description,
                'Modelo' => $log->model_type,
                'ID Modelo' => $log->model_id,
                'IP' => $log->ip_address,
                'URL' => $log->url,
                'Método' => $log->method,
                'Fecha' => $log->created_at->format('Y-m-d H:i:s'),
            ];
        })->toArray();
    }
}


