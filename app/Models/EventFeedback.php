<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventFeedback extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'comments',
        'rating',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}