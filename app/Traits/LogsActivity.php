<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            static::recordAuditLog($model, 'create');
        });

        static::updated(function ($model) {
            static::recordAuditLog($model, 'update');
        });

        static::deleted(function ($model) {
            static::recordAuditLog($model, 'delete');
        });
    }

    protected static function recordAuditLog($model, string $action): void
    {
        try {
            AuditLog::create([
                'user_id'    => Auth::id() ?? 1,
                'ip_address' => Request::ip() ?? '127.0.0.1',
                'action'     => $action,
                'module'     => class_basename($model),
                'object_id'  => $model->id ?? 0,
                'details'    => json_encode($model->getDirty()),
            ]);
        } catch (\Throwable $e) {
            // Fail silently so it never breaks the user flow
        }
    }
}
