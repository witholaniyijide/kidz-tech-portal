<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'password_change_required',
        'phone',
        'phone_country_code',
        'timezone',
        'status',
        'last_login',
        'profile_photo',
        'notify_email',
        'notify_in_app',
        'notify_daily_summary',
        'push_token',
        'device_type',
        'push_token_updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'password_change_required' => 'boolean',
        'last_login' => 'datetime',
        'notify_email' => 'boolean',
        'notify_in_app' => 'boolean',
        'notify_daily_summary' => 'boolean',
    ];

/**
     * Roles that belong to this user
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }
        return $this->roles->contains($role);
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Scope to filter users by role name
     */
    public function scopeRole($query, $role)
    {
        return $query->whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role);
        });
    }

    /**
     * Get the tutor profile associated with this user
     */
    public function tutor()
    {
        return $this->hasOne(Tutor::class, 'email', 'email');
    }

    /**
     * Get the students that this user is guardian for (parent relationship)
     */
    public function guardiansOf()
    {
        return $this->belongsToMany(Student::class, 'guardian_student', 'user_id', 'student_id')
                    ->withPivot('relationship', 'primary_contact')
                    ->withTimestamps();
    }

    /**
     * Check if this user is a parent
     */
    public function isParent(): bool
    {
        return $this->hasRole('parent');
    }

    /**
     * Check if this user is a student
     */
    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    /**
     * Check if this user is a guardian of the given student
     */
    public function isGuardianOf(Student $student): bool
    {
        return $this->guardiansOf->contains($student);
    }

    /**
     * Alias for guardiansOf() - get students this user is guardian for
     */
    public function students()
    {
        return $this->guardiansOf();
    }

    /**
     * Get only visible children for parent portal.
     * Filters out withdrawn students and those with visible_to_parent = false.
     */
    public function visibleChildren()
    {
        return $this->guardiansOf()
            ->where(function ($query) {
                $query->where('visible_to_parent', true)
                      ->orWhereNull('visible_to_parent'); // Default to visible if not set
            })
            ->whereNotIn('status', ['withdrawn']); // Hide withdrawn students
    }

    /**
     * Get manager notifications for this user
     */
    public function managerNotifications()
    {
        return $this->hasMany(ManagerNotification::class);
    }

    /**
     * Get unread manager notifications for this user
     */
    public function unreadManagerNotifications()
    {
        return $this->hasMany(ManagerNotification::class)->where('is_read', false);
    }

    /**
     * Get the appropriate title (Mr./Mrs.) based on the parent's relationship
     */
    public function getTitle(): string
    {
        // Only applicable for parents
        if (!$this->isParent()) {
            return '';
        }

        // Get the relationship from the first child (assuming same relationship for all children)
        $firstChild = $this->guardiansOf()->first();

        if (!$firstChild) {
            return '';
        }

        $relationship = strtolower($firstChild->pivot->relationship ?? '');

        return match($relationship) {
            'father' => 'Mr.',
            'mother' => 'Mrs.',
            default => '',
        };
    }

    /**
     * Get full name with title for display
     */
    public function getNameWithTitle(): string
    {
        $title = $this->getTitle();
        return $title ? "$title $this->name" : $this->name;
    }
}
