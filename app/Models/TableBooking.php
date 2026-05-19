<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableBooking extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function table()
    {
        return $this->belongsTo(TableLayout::class, 'table_id');
    }
}
