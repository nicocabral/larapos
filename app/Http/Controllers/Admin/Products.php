<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DataTables;
use App\Product;
use Validator;
class Products extends Controller
{
	public $return;

	public function index() {
		$cat = \App\Category::all();
		return view('admin.products.index')->with(['cat' => $cat]);
	}
    public function datatable(){
    	return DataTables::of(Product::get())->make(true);
    }

    public function rules($request){
    	$message = [
            'sku.required' => 'SKU/CODE is required',
            'name.required' => 'Name is required',
            'qty.required' => 'Qty is required',
            'price.required' => 'Price is required'
        ];
        $validator = Validator::make($request,[
            'sku' => 'required',
            'name' => 'required',
            'qty' => 'required',
            'price' => 'required'
        ],$message);

        $msg = false;

        if($validator->fails()) {
            $msg = $validator->getMessageBag()->toArray();
        }
        return $msg;
    }
    public function create(Request $request) {
    	$validate = $this->rules($request->all());
    	if(!$validate) {
    		$product = new Product;
    		$product->sku  = $request["sku"];
    		$product->name = $request["name"];
    		$product->description = $request["description"];
    		$product->category_id = $request["category_id"];
    		$product->status      = $request["status"];
    		$product->qty         = $request["qty"];
    		$product->price       = $request["price"];
    		$this->return = $product->save() ? ["success" => true, "message" => "Save successfully!"] : ["success" => false, "message" => "Save failed"];
    		return response()->json($this->return);
    	}
    	return response()->json(["success" => false, "message" =>$validate]);
    }

    public function read($id) {
    	return response()->json(["success" => true, "data" => Product::find($id)]);
    }

    public function update(Request $request, $id){
    	$validate = $this->rules($request->all());
    	if(!$validate) {
    		$product = Product::whereId($id)->update([
    			"name"        => $request["name"],
    			"description" => $request["description"],
    			"category_id" => $request["category_id"],
    			"qty"         => $request["qty"],
    			"price"       => $request["price"],
    			"status"      => $request["status"]
    		]);
    		$this->return = $product ? ["success" => true, "message" => "Save successfully!"] : ["success" => false, "message" => "Save failed"];
    		return response()->json($this->return);
    	}
    	return response()->json(["success" => false, "message" => $validate]);
    }

    public function delete($id) {
    	$delete = Product::whereId($id)->delete();
    	$this->return = $delete ? ["success" => true, "message" => "Deleted successfully!"] : ["success" => false, "message" => "Delete failed"];
    	return response()->json($this->return);
    }
}
