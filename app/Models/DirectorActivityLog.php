<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorActivityLog extends Model
{
    use HasFactory;

    // Disable updated_at since we only need created_at
    public $timestamps = false;

    protected $fillable = [
        'director_id',
        'action_type',
        'model_type',
        'model_id',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the director who performed the action.
     */
    public function director()
    {
        return $this->belongsTo(User::class, 'director_id');
    }

    /**
     * Get the model that was acted upon (polymorphic relationship).
     */
    public function actionable()
    {
        return $this->morphTo('model');
    }

    /**
     * Scope to get logs for a specific director.
     */
    public function scopeForDirector($query, $directorId)
    {
        return $query->where('director_id', $directorId);
    }

    /**
     * Scope to get logs for a specific action type.
     */
    public function scopeByActionType($query, $actionType)
    {
        return $query->where('action_type', $actionType);
    }

    /**
     * Scope to get logs for a specific model type.
     */
    public function scopeForModelType($query, $modelType)
    {
        return $query->where('model_type', $modelType);
    }

    /**
     * Scope to get recent logs.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Static method to log a director action.
     */
    public static function logAction(
        int $directorId,
        string $actionType,
        ?string $modelType = null,
        ?int $modelId = null,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ) {
        return self::create([
            'director_id' => $directorId,
            'action_type' => $actionType,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'ip_address' => $ipAddress ?? request()->ip(),
            'user_agent' => $userAgent ?? request()->userAgent(),
        ]);
    }
}
