<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartAddon extends Model
{
    protected $guarded = [];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function addon()
    {
        return $this->belongsTo(Addon::class);
    }
}
