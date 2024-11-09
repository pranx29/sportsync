<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Get the profiles for the department.
     */
    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }
}
