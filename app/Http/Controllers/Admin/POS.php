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
    			"ref_total" => $value["total"],
                "created_at" =>  Carbon::today()->toDateString()
    		]);
    		Product::whereId($value["id"])->update(["qty" => $value["qty"] - $value["selected_qty"]]);
    	}
    	$saleItems = SaleItems::insert($data);
    	return $saleItems;

    }

    public function salesList($filter = null) {
        $date = Carbon::today();
        $today = $filter == null ? $date->toDateString() : $filter;
     
    	return DataTables::of(Sale::with("items")->where("date",$today)->get())->make(true);
    }

    public function dashboard(){
        $date = Carbon::today();
        $today = $date->toDateString();
        $todayQuery = SaleItems::where('created_at',$today);
        $lastWeek = $date->subDays(7)->toDateString();
        $last2Week = $date->subDays(14)->toDateString();
        $last30days = $date->subDays(30)->toDateString();
        $lastWeekQuery = SaleItems::whereBetween('created_at', [$lastWeek, $today]);
        $last2WeekQuery = SaleItems::whereBetween('created_at', [$last2Week, $today]);
        $last30daysQuery = SaleItems::whereBetween('created_at', [$last30days, $today]);

        $this->return = ["success" => true, "totalQtySold" => 
                                            ["Today" => $todayQuery->sum('ref_qty'), 
                                             "Last 7 Days" => $lastWeekQuery->sum('ref_qty'), 
                                             "Last 14 Days" => $last2WeekQuery->sum('ref_qty'),
                                             "Last 30 Days" =>$last30daysQuery->sum('ref_qty')
                                            ],
                                            "totalSalesAmount" => [
                                                "Today" => $todayQuery->sum('ref_total'), 
                                                 "Last 7 Days" => $lastWeekQuery->sum('ref_total'), 
                                                 "Last 14 Days" => $last2WeekQuery->sum('ref_total'),
                                                 "Last 30 Days" =>$last30daysQuery->sum('ref_total')
                                                
                                            ]
                        ];
        return response()->json($this->return);
    }
}
