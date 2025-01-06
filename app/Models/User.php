<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    const ADMIN = 'admin';
    const EMPLOYEE = 'employee';
    const ROLES = [self::ADMIN, self::EMPLOYEE];
    const DEFAULT_PASSWORD = 'password';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'is_active',
        'is_profile_created',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the profile associated with the user.
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Get the department associated with the user.
     */

    public function department()
    {
        return $this->belongsTo(Department::class);
    }


    /**
     * Get the role associated with the user.
     */

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the sports associated with the user.
     */

    public function sports()
    {
        return $this->belongsToMany(Sport::class, 'employee_sport');
    }

    /**
     * Get the availability associated with the user.
     */

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }

    /**
     * Get the groups associated with the user.
     */

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user');
    }

    /**
     * Get the messages associated with the user.
     */

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function sessions()
    {
        return $this->belongsToMany(Session::class, 'session_user')->withTimestamps();
    }

    public function sessionFeedbacks()
    {
        return $this->hasMany(SessionFeedback::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_user');
    }

    public function teams()
    {
        return $this->belongsToMany(EventTeam::class, 'event_user');
    }
}
