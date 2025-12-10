<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentNotification extends Model
{
    use HasFactory;

    protected $table = 'parent_notifications';

    protected $fillable = [
        'parent_id',
        'student_id',
        'type',
        'title',
        'message',
        'link',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * Notification types
     */
    const TYPE_REPORT_APPROVED = 'report_approved';
    const TYPE_CERTIFICATE_ISSUED = 'certificate_issued';
    const TYPE_MESSAGE_RECEIVED = 'message_received';
    const TYPE_MILESTONE_ACHIEVED = 'milestone_achieved';
    const TYPE_PROGRESS_UPDATE = 'progress_update';

    /**
     * Get the parent (user) that owns this notification
     */
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Get the student associated with this notification
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    /**
     * Check if notification is unread
     */
    public function isUnread()
    {
        return is_null($this->read_at);
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for notifications of a specific type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to order by most recent
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Create a notification for a parent
     */
    public static function notify(
        int $parentId,
        string $type,
        string $title,
        string $message,
        ?string $link = null,
        ?int $studentId = null,
        array $data = []
    ): self {
        return static::create([
            'parent_id' => $parentId,
            'student_id' => $studentId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'data' => $data,
        ]);
    }
}
