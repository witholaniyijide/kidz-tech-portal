<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorTodo extends Model
{
    use HasFactory;

    protected $fillable = [
        'tutor_id',
        'title',
        'description',
        'priority',
        'completed',
        'completed_at',
        'due_date',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'completed_at' => 'datetime',
        'due_date' => 'date',
    ];

    /**
     * Get the tutor that owns this todo.
     */
    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    /**
     * Scope for incomplete todos.
     */
    public function scopeIncomplete($query)
    {
        return $query->where('completed', false);
    }

    /**
     * Scope for completed todos.
     */
    public function scopeCompleted($query)
    {
        return $query->where('completed', true);
    }

    /**
     * Scope ordered by priority.
     */
    public function scopeOrderByPriority($query)
    {
        return $query->orderByRaw("FIELD(priority, 'high', 'medium', 'low')");
    }
}
