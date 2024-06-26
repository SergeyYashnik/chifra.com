<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Catalog extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'catalogs';
    protected $fillable = [
        'name', 'image',
    ];

    public function productsLvl1()
    {
        return $this->hasMany(Product::class, 'catalogs_lvl_1');
    }

    public function productsLvl2()
    {
        return $this->hasMany(Product::class, 'catalogs_lvl_2');
    }

    public function productsLvl3()
    {
        return $this->hasMany(Product::class, 'catalogs_lvl_3');
    }

}
