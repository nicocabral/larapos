<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DataTables;
use App\Category;
use Validator;
class Categories extends Controller
{
    protected $return;

    public function index() {
    	return view('admin.categories.index');
    }

    public function datatable(){
    	return DataTables::of(Category::get())->make(true);
    }

    public function rules($request){
    	$message = [
            'name.required' => 'Name is required',
        ];
        $validator = Validator::make($request,[
            'name' => 'required',
         
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
    		$category = new Category;
    		$category->name = $request["name"];
    		$this->return = $category->save() ? ["success" => true, "message" => "Save successfully!"] : ["success" => false, "message" => "Save failed"];
    		return response()->json($this->return);
    	}
    	return response()->json(["success" => false, "message" =>$validate]);
    }
    public function read($id) {
    	$category = Category::find($id);
    	return response()->json(['success' => $category ? true : false, "data" => $category]);
    }
    public function update(Request $request,$id) {
    	$validate = $this->rules($request->all());
    	if(!$validate) {
    		$category = Category::whereId($id)->update([
    			"name" => $request["name"]
    		]);
    		$this->return = $category ? ["success" => true, "message" => "Save successfully!"] : ["success" => false, "message" => "Save failed"];
    		return response()->json($this->return);
    	}
    	return response()->json(["success" => false, "message" =>$validate]);
    }

    public function delete($id) {
    	$delete = Category::whereId($id)->delete();
    	$this->return = $delete ? ["success" => true, "message" => "Deleted successfully!"] : ["success" => false, "message" => "Delete failed"];
    	return response()->json($this->return);
    }
}
