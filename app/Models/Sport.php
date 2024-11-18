<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'focus',
    ];

    public function user()
    {
        return $this->belongsToMany(User::class, 'employee_sport');
    }

    public function group()
    {
        return $this->hasOne(Group::class);
    }

    
    

}
