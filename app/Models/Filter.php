<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    use HasFactory;
    protected $table = 'filters';
    protected $fillable = [
        'name',
        'id_catalog',
        'is_custom_input',
        'required_to_fill_out',
    ];
    public function subfilters()
    {
        return $this->hasMany(Filter::class, 'id_filter');
    }
}
