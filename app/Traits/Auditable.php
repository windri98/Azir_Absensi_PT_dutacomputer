<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            self::logAudit('create', $model, null, $model->getAttributes());
        });

        static::updated(function ($model) {
            $oldValues = $model->getOriginal();
            $newValues = $model->getAttributes();
            
            // Only log if something actually changed
            $changes = array_diff_assoc($newValues, $oldValues);
            if (!empty($changes)) {
                self::logAudit('update', $model, $oldValues, $newValues);
            }
        });

        static::deleted(function ($model) {
            self::logAudit('delete', $model, $model->getAttributes(), null);
        });
    }

    protected static function logAudit($action, $model, $oldValues, $newValues)
    {
        try {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'model' => class_basename($model),
                'model_id' => $model->id,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        } catch (\Exception $e) {
            // Log audit failure silently to prevent breaking the main operation
            \Log::error('Audit logging failed: ' . $e->getMessage());
        }
    }
}
