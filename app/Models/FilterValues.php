<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilterValues extends Model
{
    use HasFactory;

    public function connectionProductFilterValue()
    {
        return $this->hasMany(ConnectionProductFilterValue::class, 'filter_value_id');
    }
}
