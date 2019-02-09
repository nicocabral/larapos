<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = ['sku', 'name', 'description', 'category_id','qty','price','status'];
}
