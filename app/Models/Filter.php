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
    public function filterValue()
    {
        return $this->hasMany(FilterValues::class, 'id_filter');
    }

    public function requiredSubfilters()
    {
        return $this->hasMany(Filter::class, 'id_filter')
            ->where('required_to_fill_out', 1);
    }





}
