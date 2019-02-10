<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleItems extends Model
{
    protected $table = 'sale_items';
 
    protected $fillable = ["sale_id","ref_id","ref_name","ref_description", "ref_qty","ref_price", "ref_total"];
    public function sale() {
    	return $this->belongsTo('App\Sale');
    }
}
