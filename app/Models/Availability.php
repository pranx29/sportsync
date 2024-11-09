<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'day', 'time_slot'];

    // Define the days of the week
    const DAYS = [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday'
    ];

    // Define the time slots
    const TIME_SLOTS = [
        '6-9',
        '9-12',
        '12-15',
        '15-18',
        '18-21',
        '21-24'
    ];




    /**
     * Get the profiles for the department.
     */
    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }
}
