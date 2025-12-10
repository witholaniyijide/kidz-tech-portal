<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'student_id',
        'subject',
        'body',
        'is_read',
        'read_at',
        'parent_id', // For threading/replies
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Get the sender of this message.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the recipient of this message.
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Get the student this message is about (if any).
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the parent message (for replies).
     */
    public function parent()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    /**
     * Get replies to this message.
     */
    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_id');
    }

    /**
     * Scope to get unread messages.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope to get messages for a user (sent or received).
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('sender_id', $userId)
            ->orWhere('recipient_id', $userId);
    }

    /**
     * Mark message as read.
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
}
