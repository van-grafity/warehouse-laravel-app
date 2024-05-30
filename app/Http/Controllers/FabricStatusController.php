<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Packinglist;
use App\Models\FabricRoll;
use App\Models\FabricRollRack;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Yajra\Datatables\Datatables;

use App\Exports\InstoreReportExport;
use Maatwebsite\Excel\Facades\Excel;

class FabricStatusController extends Controller
{
 public function __construct()
    {
        Gate::define('manage', function ($user) {
            return $user->hasPermissionTo('fabric-status.manage');
        });
        Gate::define('print', function ($user) {
            return $user->hasPermissionTo('instore-report.print');
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {    
        $packinglist = Packinglist::select('gl_number')->distinct()->get();
       
        $data = [
            'title' => 'Fabric Status',
            'page_title' => 'Fabric Status',
            'packinglist' => $packinglist,
            'can_manage' => auth()->user()->can('manage'),
        ];
        return view('pages.fabric-status.index', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $packinglist_id)
    {
        try {
            $fabric_rolls = FabricRoll::leftJoin('fabric_roll_racks','fabric_roll_racks.fabric_roll_id','=','fabric_rolls.id')
            ->leftJoin('racks','racks.id','=','fabric_roll_racks.rack_id')
            ->leftJoin('rack_locations','rack_locations.rack_id','=','fabric_roll_racks.rack_id')
            ->leftJoin('locations','locations.id','=','rack_locations.location_id')
            
            ->where('fabric_rolls.packinglist_id', $packinglist_id)
            ->select(
                'fabric_rolls.id',
                'fabric_rolls.roll_number',
                'fabric_rolls.serial_number',
                'fabric_rolls.kgs',
                'fabric_rolls.lbs', 
                'fabric_rolls.yds',
                'fabric_rolls.width',
                'racks.serial_number as rack_number',        
                'locations.location as rack_location',
            )
            ->get();

            $packinglist = Packinglist::with('color', 'invoice')->find($packinglist_id);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully get packinglist (' . $packinglist->packinglist . ')',
                'data' => [
                    'packinglist' => $packinglist,
                    'fabric_rolls' => $fabric_rolls,
                ]
            ];
            return response()->json($data_return, 200);
        } catch (\Throwable $th) {
            $data_return = [
                'status' => 'error',
                'message' => $th->getMessage(),
            ];
            return response()->json($data_return);
        }
    }

    // public function store(Request $request, string $id)
    // {
    //     try {
    //         $selected_roll_ids = explode(',',$request->selected_roll_id);

    //         $updated_racks = [];
    //         DB::transaction(function () use ($selected_roll_ids, &$updated_racks) {
             
    //             $rack_location = FabricRollRack::whereIn('rack_id', $selected_roll_ids);

    //             foreach ($selected_roll_ids as $key => $rack_id) {
    //                 $data_rack = FabricRollRack::firstOrCreate([
    //                     'rack_id' => $rack_id,
    //                 ]);                           
    //                 $rack = Rack::find($rack_id);
    //                 $rack->save();
    //                 $inserted_rack[] = $rack;
    //             }
    //             });

    //         $data_return = [
    //             'status' => 'success',
    //             'message' => 'Successfully updated Rack ',
    //             'data' => [
    //                 'updated_racks' => $updated_racks
    //             ]
    //         ];
    //         return response()->json($data_return, 200);

    //     } catch (\Throwable $th) {
    //         $data_return = [
    //             'status' => 'error',
    //             'message' => $th->getMessage(),
    //         ];
    //         return response()->json($data_return);
    //     }
    // }

    public function update(Request $request, string $id)
    {
        try {
            $fabric_roll_rack = FabricRollRack::find($id);
            $fabric_roll_rack->rack_id = $request->rack_id;
            $fabric_roll_rack->save();
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully updated Rack',
                'data' => $fabric_roll_rack
            ];
            return response()->json($data_return, 200);
        } catch (\Throwable $th) {
            $data_return = [
                'status' => 'error',
                'message' => $th->getMessage(),
            ];
            return response()->json($data_return);
        }
    }

    /**
     * Show Datatable Data.
     */
    public function dtable(Request $request)
    {
        $query = Packinglist::query();
   
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                $action_button = "
                    <a href='". route('fabric-status.detail',$row->id)."' class='btn btn-primary btn-xs' >Detail</a>".                  
                    '<a href="javascript:void(0);" class="btn btn-primary btn-xs" onclick="show_modal_detail(\'modal_fabric_status\', '.$row->id.')">Quick Detail</a>';                 
                return $action_button;
            })

            ->filter(function ($query, $gl_filter){
                if (request('gl_filter')) {
                        $query->where('gl_number', request()->gl_filter)->get();
                    }

                if (request('color_filter')) {
                        $query->where('color_id', request()->color_filter)->get();
                    }
                
            }, true)
           
            ->addColumn('serial_number', function($row){
                $serial_number = "<a href='". route('packinglist.detail',$row->id)."' class='' data-toggle='tooltip' data-placement='top' title='Click for Detail'>$row->serial_number</a>";
                return $serial_number;
            })
            ->addColumn('invoice', function($row){
                return $row->invoice->invoice_number;
            })
            ->addColumn('color', function($row){
                return $row->color->color;
            })
            ->addColumn('total_length_yds', function($row){
                $PackinglistModel = new Packinglist;
                $total_length_yds = $PackinglistModel->getRollSummaryInPackinglist($row->id,'stock_in');
                return ($total_length_yds ? $total_length_yds->total_length_yds : 0) . ' yds';
            })
            ->addColumn('total_weight_kgs', function($row){
                $PackinglistModel = new Packinglist;
                $total_weight_kgs = $PackinglistModel->getRollSummaryInPackinglist($row->id,'stock_in');
                return ($total_weight_kgs ? $total_weight_kgs->total_weight_kgs : 0) . ' kgs';
            })
            ->addColumn('roll_balance', function($row){
                $PackinglistModel = new Packinglist;
                $stock_in_roll = $PackinglistModel->getRollSummaryInPackinglist($row->id,'stock_in');
                return $stock_in_roll ? $stock_in_roll->total_roll : 0;
            })

            ->toJson();
    }

    /**
     * Show detail page of resource.
     */
    public function detail(string $id)
    { 
        $packinglist = Packinglist::find($id);
        $data = [
            'title' => 'Fabric Status',
            'page_title' => 'Fabric Status',
            'packinglist' => $packinglist,
        ];
        return view('pages.fabric-status.detail', $data);
    }

    /**
     * Show Datatable Data.
     */
    public function dtable_roll_list()
    {
        $packinglist_id = request()->packinglist_id;
        $query = FabricRoll::leftJoin('fabric_roll_racks','fabric_roll_racks.fabric_roll_id','=','fabric_rolls.id')
            ->leftJoin('racks','racks.id','=','fabric_roll_racks.rack_id')
            ->where('fabric_rolls.packinglist_id', $packinglist_id)
            ->where('fabric_rolls.racked_by','!=', null)
            ->select(
                'fabric_rolls.id', 
                'fabric_rolls.roll_number', 
                'fabric_rolls.serial_number', 
                'fabric_rolls.kgs', 
                'fabric_rolls.lbs', 
                'fabric_rolls.yds',
                'fabric_rolls.width',  
                'racks.serial_number as rack_number'
            )
            ->get();
        
        return Datatables::of($query)
                ->addIndexColumn()
                ->escapeColumns([])
                ->addColumn('checkbox', function ($row) {
                        if($row->change_rack_id) { return null; }
                        
                        $checkbox_element = '
                            <div class="form-group mb-0">
                                <div class="custom-control custom-checkbox">
                                    <input 
                                        id="roll_checkbox_'. $row->id .'" 
                                        name="selected_roll[]" 
                                        class="custom-control-input checkbox-roll-control" 
                                        type="checkbox" 
                                        value="'. $row->id .'"
                                        data-roll-number="'. $row->roll_number .'"
                                        onchange="checkbox_clicked()" 
                                    >
                                    <label for="roll_checkbox_'. $row->id .'" class="custom-control-label"></label>
                                </div>
                            </div>
                        ';
                        return $checkbox_element;
                    })
                    ->toJson();

    }
    
    // ## Export Instore Report
    public function export(Request $request, int $id)
    {   
        $packinglist = Packinglist::find($id);
        
        return Excel::download(new InstoreReportExport, 'Instore-Report.xlsx');
    }
}