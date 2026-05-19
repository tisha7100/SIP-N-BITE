<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableLayout extends Model
{
    protected $table = 'tables_layout';

    protected $fillable = [
        'table_number',
        'capacity',
        'is_active',
        'status',
        'x_pos',
        'y_pos',
    ];
}
