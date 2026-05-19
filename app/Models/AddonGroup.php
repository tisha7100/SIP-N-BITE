<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddonGroup extends Model
{
    protected $fillable = [
        'name',
        'type',
        'is_required'
    ];

    public function addons()
    {
        return $this->hasMany(Addon::class);
    }
}