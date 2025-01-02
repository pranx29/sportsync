<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'eventName',
        'eventDescription',
        'sportType',
        'eventImage',
        'registrationType',
        'maxParticipants',
        'registrationDeadline',
        'numberOfTeams',
        'teamAssignment',
        'venue',
        'customLocation',
        'customLocationName',
        'customLocationLink',
        'locationType',
        'eventDate',
        'startTime',
        'endTime',
        'rulesDocument',
        'rulesDescription',
        'notifyCreation',
        'sendReminder',
        'notifyAssignments',
    ];

    protected $casts = [
        'customLocation' => 'boolean',
        'eventDate' => 'date',
        'notifyCreation' => 'boolean',
        'sendReminder' => 'boolean',
        'notifyAssignments' => 'boolean',
    ];
}
