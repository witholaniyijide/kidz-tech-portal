<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyClassSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_date',
        'day_name',
        'classes',
        'rescheduled_classes',
        'status',
        'posted_by',
        'posted_at',
        'footer_note',
        'repeat_weekly',
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'classes' => 'array',
        'rescheduled_classes' => 'array',
        'posted_at' => 'datetime',
        'repeat_weekly' => 'boolean',
    ];

    public function poster()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}
