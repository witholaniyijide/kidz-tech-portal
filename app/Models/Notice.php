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
        'author_id',
        'published_at',
        'publish_at',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'visible_to' => 'array',
        'published_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}
