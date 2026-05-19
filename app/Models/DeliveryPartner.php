<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class DeliveryPartner extends Authenticatable
{
    protected $table = 'delivery_partners';

    protected $fillable = [
        'name',
        'email',
        'password',
        'status'
    ];

    protected $hidden = [
        'password'
    ];
}