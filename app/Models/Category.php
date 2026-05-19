<?php

namespace App\Models;
use App\Models\AddonGroup;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'is_customizable',
    ];

    public function foods()
{
    return $this->hasMany(Food::class);
}
   public function addonGroups()
{
    return $this->belongsToMany(
        AddonGroup::class,
        'category_addon_groups'
    );
}
}