<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectionProductFilterValue extends Model
{
    use HasFactory;

    public function filterValue()
    {
        return $this->belongsTo(FilterValues::class, 'filter_value_id');
    }

}
