<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorReportComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'user_id',
        'comment',
        'role',
    ];

    /**
     * Get the report that this comment belongs to.
     */
    public function report()
    {
        return $this->belongsTo(TutorReport::class, 'report_id');
    }

    /**
     * Get the user who made this comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
