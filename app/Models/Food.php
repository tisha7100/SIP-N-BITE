<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $table = 'foods'; // ✅ FORCE correct table

    protected $fillable = [
        'name',
        'category_id',
        'price',
        'description',
        'image',
        'is_customizable',
        'delivery_time',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function addons()
    {
        return $this->hasMany(Addon::class, 'food_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'food_id');
    }
}