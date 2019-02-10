<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = 'sales';
    protected $timestamp = [];
    protected $fillble = ["total", "date","status"];


    public function items(){
    	return $this->hasMany('App\SaleItems');
    }
}
