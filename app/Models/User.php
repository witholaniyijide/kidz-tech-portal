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
        'phone',
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
}
