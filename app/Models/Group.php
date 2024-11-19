<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function getImageAttribute($value)
    {

        // return the image or a default image if the image is not found

        // if $value is a https link, return the link
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        return $value ? Storage::url($value) : url('https://www.ibcs.com/wp-content/uploads/2024/01/Projekt-bez-nazwy-15.png');
    }

}
