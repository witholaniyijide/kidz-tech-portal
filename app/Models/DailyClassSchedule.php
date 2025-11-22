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
        'status',
        'posted_by',
        'posted_at',
        'footer_note',
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'classes' => 'array',
        'posted_at' => 'datetime',
    ];

    public function poster()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}
