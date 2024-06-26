<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function images()
    {
        return $this->hasMany(ImageProduct::class, 'id_product');
    }

    public function oneImage()
    {
        return $this->hasOne(ImageProduct::class, 'id_product')->latest();
    }

    public function connectionProductFilterValues()
    {
        return $this->hasMany(ConnectionProductFilterValue::class, 'product_id');
    }
}
