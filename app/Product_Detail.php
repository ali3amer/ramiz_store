<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_Detail extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

}
