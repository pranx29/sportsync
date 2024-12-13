<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Session extends Model
{
    use HasFactory;

    protected $casts = [
        'equipment_provided' => 'boolean',
    ];

    protected $table = 'group_sessions';

    protected $fillable = [
        'group_id',
        'session_name',
        'date_time',
        'duration',
        'participation_limit',
        'equipment_provided',
        'location',
        'description',
        'leader_id',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'session_user')->withTimestamps();
    }

    public function feedbacks()
    {
        return $this->hasMany(SessionFeedback::class, 'session_id')->orderBy('created_at', 'desc');
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

}
