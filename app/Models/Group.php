<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'user_id',
        'image',
        'sport_id',
        'is_active',
    ];

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    
}
