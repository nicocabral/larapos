<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Sale;
use App\SaleItems;
use Carbon\Carbon;
use App\Product;
use DataTables;
class POS extends Controller
{
	protected $sale_id;
	protected $return;
    public function index() {
    	return view('admin.pos.index');
    }

    public function create(Request $request) {

    	if(count($request["items"]) < 0) {
    		return response()->json(["success" => false, "message" => "Item is empty","request" => $request->all()]);
    	}
    	$sale = new Sale;
    	$sale->total = $request["total_amount"];
    	$sale->date  = Carbon::now();
    	$sale->paid_amount = $request["paid_amount"];
    	$sale->status = $request["status"];
    	$sale->save();
    	$this->sale_id = $sale->id;

    	$this->return =  $this->saveItems($request["items"]) ? ["success" => true, "message" => "Save sucessfully"] : ["success" => false, "message" => "Save failed"];
    	
    	return response()->json($this->return);

    }

    public function saveItems($items) {
    	$data = [];
    	$product = new Product;
    	foreach($items as $key => $value) {
    		array_push($data, [
    			"sale_id" => $this->sale_id,
    			"ref_id"  => $value["id"],
    			"ref_sku" => $value["sku"],
    			"ref_name" => $value["name"],
    			"ref_description" => $value["description"],
    			"ref_qty" => $value["selected_qty"],
    			"ref_price" => $value["price"],
    			"ref_total" => $value["total"]
    		]);
    		Product::whereId($value["id"])->update(["qty" => $value["qty"] - $value["selected_qty"]]);
    	}
    	$saleItems = SaleItems::insert($data);
    	return $saleItems;

    }

    public function salesList() {
    	return DataTables::of(Sale::with("items")->get())->make(true);
    }
}
