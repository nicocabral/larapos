<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DataTables;
use Validator;
use App\User;
class Users extends Controller
{
	protected $password;
	protected $username;
	protected $return;
    public function index() {
    	return view('admin.users.index');
    }


    public function datatable() {
    	return DataTables::of(User::where("id", "!=",Auth::user()->id)->get())->make(true);
    }
    public function rules($request){
    	$message = [
            'first_name.required' => 'FIRST NAME is required',
            'last_name.required' => 'LAST NAME is required',
            'contact_number.required' => 'CONTACT NUMBER is required',
        ];
        $validator = Validator::make($request,[
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_number' => 'required',
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
    		$this->password = $this->genPassword();
    		$this->username = $request["first_name"].'.'.$request["last_name"];
    		if(User::whereUsername($this->username)->first()) {
    			return response()->json(["success" => false, "message" => "User already exist"]);
    		}
    		$user = new User;
    		$user->username  = $this->username;
    		$user->first_name = $request["first_name"];
    		$user->last_name = $request["last_name"];
    		$user->contact_number = $request["contact_number"];
    		$user->status      = $request["status"];
    		$user->password   = bcrypt($this->password);
    		$userdata = ["username" => $this->username, "password" => $this->password];
    		$this->return = $user->save() ? ["success" => true, "message" => "Username : ".$this->username. "\nPassword : ".$this->password, "userData"=>$userdata] : ["success" => false, "message" => "Save failed"];
    		return response()->json($this->return);
    	}
    	return response()->json(["success" => false, "message" =>$validate]);
    }

    public function read($id) {
    	return response()->json(["success" => true, "data" => User::find($id)]);
    }


    public function update(Request $request,$id) {
    	$validate = $this->rules($request->all());
    	if(!$validate) {
    		$user = User::whereId($id)->update(["first_name" => $request["first_name"], "last_name" => $request["last_name"], "contact_number" => $request["contact_number"], "status" => $request["status"]]);
    		$this->return = $user ? ["success" => true, "message" => "Save successfully!"] : ["success" => false, "message" => "Save failed"];
    		return response()->json($this->return);
    	}
    	return response()->json(["success" => false, "message" =>$validate]);
    }

    public function delete($id) {
    	$delete = User::whereId($id)->delete();
    	$this->return = $delete ? ["success" => true, "message" => "Deleted successfully!"] : ["success" => false, "message" => "Delete failed"];
    	return response()->json($this->return);
    }

    public function genPassword($args = null){
		$this->password = $args ? $args : uniqid(mt_rand());
	    return '_'.substr(base_convert(sha1($this->password), 16, 36), 0, 8);
	}

	public function resetPassword($id) {
		$this->password = $this->genPassword();
		$user = User::whereId($id)->update(["password" => bcrypt($this->password)]);
		$this->return = $user ? ["success" => true, "message" => "New password : ".$this->password] : ["success" => false, "message" => "Reset failed"];
		return response()->json($this->return);
	}

	public function updateMyaccount(Request $request) {
		$user = User::whereId(Auth::user()->id)->update(["first_name" => $request["first_name"], 
														 "last_name" => $request["last_name"],
														 "contact_number" => $request["contact_number"],
														 "status" => $request["status"],
														 "username" => $request["username"]]);
		$this->return = $user ? ["success" => true, "message" => "Updated successfully!"] : ["success" => false, "message" => "Update failed"];
		return response()->json($this->return);
	}
}
