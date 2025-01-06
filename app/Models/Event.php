<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    const STATUS_UPCOMING = 'upcoming';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELED = 'canceled';

    protected $fillable = [
        'name',
        'description',
        'sport_id',
        'image',
        'registration_type',
        'max_participants',
        'registration_deadline',
        'number_of_teams',
        'team_assignment',
        'venue_id',
        'custom_location_name',
        'custom_location_link',
        'location_type',
        'event_date',
        'start_time',
        'end_time',
        'rules_description',
        'rules_document',
        'notify_creation',
        'send_reminder',
        'notify_assignments',
        'status'
    ];

    protected $casts = [
        'registration_deadline' => 'date',
        'event_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'notify_creation' => 'boolean',
        'send_reminder' => 'boolean',
        'notify_assignments' => 'boolean',
    ];
    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    // The event can have many teams
    public function teams()
    {
        return $this->hasMany(EventTeam::class);
    }

    // If the event is individual, participants are users directly associated with the event
    public function users()
    {
        return $this->belongsToMany(User::class, 'event_user');
    }

    // return image URL
    public function getImageAttribute($value)
    {

        // return the image or a default image if the image is not found

        // if $value is a https link, return the link
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        return $value ? Storage::url($value) : url('https://www.ibcs.com/wp-content/uploads/2024/01/Projekt-bez-nazwy-15.png');
    }

    public static function markCompletedEvents()
    {
        self::whereRaw("(event_date || ' ' || end_time) < ?", [now()])
            ->where('status', '!=', self::STATUS_COMPLETED)
            ->update(['status' => self::STATUS_COMPLETED]);
    }

    public function isUserRegistered($user)
    {
        return $this->users->contains($user);
    }
}
