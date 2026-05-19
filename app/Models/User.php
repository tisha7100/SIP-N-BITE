<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'registration_time' => 'datetime',
        'login_time' => 'datetime',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    // Removed the hashed cast to support plain text as per requirement
}
