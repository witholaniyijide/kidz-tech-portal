<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notice extends Model
{
    use HasFactory;

    protected $table = 'notice_board';

    protected $fillable = [
        'title',
        'content',
        'priority',
        'visible_to',
        'posted_by',
        'published_at',
        'status',
        'is_pinned',
        'pinned_at',
        'pinned_by',
    ];

    protected $casts = [
        'visible_to' => 'array',
        'published_at' => 'datetime',
        'is_pinned' => 'boolean',
        'pinned_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function pinnedByUser()
    {
        return $this->belongsTo(User::class, 'pinned_by');
    }
}
