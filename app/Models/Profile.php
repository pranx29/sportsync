<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Storage;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'role_id',
        'gender',
        'profile_image',
        'date_of_birth',
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the department associated with the profile.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the role associated with the profile.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the profile image attribute.
     */

    public function getProfileImageAttribute($value)
    {

        return $value ? Storage::url($value) : '';
    }
}
