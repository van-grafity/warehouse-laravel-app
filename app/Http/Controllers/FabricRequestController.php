<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FabricRequest;
use App\Models\FabricRollRack;
use App\Models\FabricRoll;
use App\Models\Packinglist;
use App\Models\FabricIssuance;
use App\Models\Color;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Yajra\Datatables\Datatables;


class FabricRequestController extends Controller
{
    public function __construct()
    {
        Gate::define('manage', function ($user) {
            return $user->hasPermissionTo('fabric-request.manage');
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $data = [
            'title' => 'Fabric request',
            'page_title' => 'Fabric request',
            'can_manage' => auth()->user()->can('manage'),
        ];
        return view('pages.fabric-request.index', $data);
    }


    /**
     * Show Datatable Data.
     */
    public function dtable()
    {
        $query = FabricRequest::query();
        
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                $action_button = "
                    <a href='". route('fabric-request.issue-fabric',$row->id)."' class='btn btn-primary btn-sm' >Issue Fabric</a>                ";
                return $action_button;
            })
            ->addColumn('serial_number', function($row){
                $serial_number = "<a href='". route('fabric-request.detail',$row->id)."' class='' data-toggle='tooltip' data-placement='top' title='Click for Detail'>$row->fbr_serial_number</a>";
                return $serial_number;
            })
            ->toJson();
    }

    public function detail(string $id)
    {
        $fabric_request = FabricRequest::find($id);
        $data = [
            'title' => 'Fabric Request Detail',
            'page_title' => 'Fabric Request Detail',
            'fabric_request' => $fabric_request,
        ];
        return view('pages.fabric-request.detail', $data);
        
    }

    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $fabric_request = FabricRequest::find($id);
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully get Fabric Request',
                'data' => [
                    'fabric_request' => $fabric_request,
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

    /**
     * Syncronize data with Cutting App.
     */
    public function sync(Request $request)
    {
        try {
            $fabric_requests = $request->fabric_requests;
            $fbr_counter = 0;
            
            DB::transaction(function () use ($fabric_requests, &$fbr_counter) {
                $FabricRequestModel = new FabricRequest;
                foreach ($fabric_requests as $key_fbr => $fabric_request) {
                    $fabric_request = (object) $fabric_request;
                    $FabricRequestModel->check_serial_number($fabric_request);
                    $is_fabric_request_exist = $FabricRequestModel->isFabricRequestExist($fabric_request->fbr_id);
                    
                    $fabric_request_data = [
                        'fbr_serial_number' => $fabric_request->fbr_serial_number,
                        'fbr_status_print' => $fabric_request->fbr_status_print,
                        'fbr_remark' => $fabric_request->fbr_remark,
                        'fbr_created_at' => $fabric_request->fbr_created_at,
                        'fbr_updated_at' => $fabric_request->fbr_updated_at,
                        'gl_number' => $fabric_request->gl_number,
                        'color' => $fabric_request->color,
                        'style' => $fabric_request->style,
                        'fabric_type' => $fabric_request->fabric_type,
                        'fabric_po' => $fabric_request->fabric_po,
                        'laying_planning_id' => $fabric_request->laying_planning_id,
                        'laying_planning_serial_number' => $fabric_request->laying_planning_serial_number,
                        'laying_planning_detail_id' => $fabric_request->laying_planning_detail_id,
                        'table_number' => $fabric_request->table_number,
                        'qty_required' => $fabric_request->qty_required,
                        'last_sync_by' => auth()->user()->id,
                        'last_sync_at' => date('Y-m-d H:i:s'),
                    ];
                    
                    if(!$is_fabric_request_exist) {
                        $fabric_request_data['fbr_id'] = $fabric_request->fbr_id;
                        FabricRequest::firstOrCreate($fabric_request_data);
                    } else {
                        $fabric_request = FabricRequest::where('fbr_id', $fabric_request->fbr_id)->update($fabric_request_data);
                    }
    
                    $fbr_counter++;
                }
            });

            $data_return = [
                'status' => 'success',
                'message' => "Successfully Sync {$fbr_counter} Fabric Requests",
                'data' => [
                    'fabric_requests' => $fabric_requests,
                ]
            ];
            return response()->json($data_return);

        } catch (\Throwable $th) {
            $data_return = [
                'status' => 'error',
                'message' => $th->getMessage(),
            ];
            return response()->json($data_return);
        }
    }

    public function issue_fabric(string $id)
    {
        $fabric_request = FabricRequest::find($id);
        $fabric_request->qty_issued = $fabric_request->allocatedFabricRolls->sum('yds');
        $fabric_request->qty_difference = $fabric_request->qty_issued - $fabric_request->qty_required;
        $allocated_fabric_roll = $fabric_request->allocatedFabricRolls;
        
        // todo : sorting lagi attribute yang akan dilempar ke view
        $allocated_fabric_roll->map(function ($fabric_roll) {
            $fabric_roll->color = $fabric_roll->packinglist->color->color;
            $fabric_roll->batch = $fabric_roll->packinglist->batch_number;
            $fabric_roll->rack_number = $fabric_roll->rack->serial_number;
            $fabric_roll->location = $fabric_roll->rack->location->location;
        });
        
        $gl_numbers = Packinglist::select('gl_number')->distinct()->get();
        $is_gl_number_exist = in_array($fabric_request->gl_number, $gl_numbers->pluck('gl_number')->toArray());

        $color = Color::where('color', 'like', '%' . $fabric_request->color . '%')->first();
        $color_id = $color ? $color->id : null;

        $batch_numbers = Packinglist::where('gl_number', $fabric_request->gl_number);
        if ($color_id !== null) {
            $batch_numbers->where('color_id', $color_id);
        }
        $batch_numbers = $batch_numbers->select('batch_number')
            ->distinct()->get();

        $data = [
            'title' => 'Fabric Issue',
            'page_title' => 'Fabric Issue',
            'fabric_request' => $fabric_request,
            'gl_numbers' => $gl_numbers,
            'is_gl_number_exist' => $is_gl_number_exist,
            'color_id' => $color_id,
            'batch_numbers' => $batch_numbers,
            'allocated_fabric_roll' => $allocated_fabric_roll,
        ];
        return view('pages.fabric-request.issue-fabric', $data);
    }


    public function issue_fabric_store(Request $request, $fbr_id)
    {
        try {
            $fabric_request = FabricRequest::find($fbr_id);
            $fabric_roll_ids = $request->confirmed_fabric_roll;
            
            // ## allocate fabric roll to fbr. insert into relation table fabric_issuances
            DB::beginTransaction();
            $inserted_roll = collect($fabric_roll_ids)->map(function ($fabric_roll_id) use ($fabric_request) {
                return FabricIssuance::firstOrCreate([
                    'fabric_request_id' => $fabric_request->id,
                    'fabric_roll_id' => $fabric_roll_id,
                ]);  
            })->all();
            
            // ## update fabric_requests it self
            $fabric_request->issued_at = Carbon::now();
            $fabric_request->save();
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully allocate ' . count($inserted_roll) . ' fabric roll to ' . $fabric_request->fbr_serial_number,
                'data' => [
                    'fabric_request' => $fabric_request,
                    'inserted_roll' => $inserted_roll,
                ]
            ];
            DB::commit();
            return response()->json($data_return, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            $data_return = [
                'status' => 'error',
                'message' => $th->getMessage(),
            ];
            return response()->json($data_return);
        }
    }

    public function dtable_roll_list()
    {
        $query = FabricRoll::leftJoin('fabric_issuances', 'fabric_rolls.id', '=', 'fabric_issuances.fabric_roll_id')
            ->join('fabric_roll_racks', 'fabric_roll_racks.fabric_roll_id', '=', 'fabric_rolls.id')
            ->join('racks', 'racks.id', '=', 'fabric_roll_racks.rack_id')
            ->join('rack_locations', 'rack_locations.rack_id', '=', 'fabric_roll_racks.rack_id')
            ->join('locations', 'locations.id', '=', 'rack_locations.location_id')
            ->join('packinglists', 'packinglists.id', '=', 'fabric_rolls.packinglist_id')
            ->join('colors', 'colors.id', '=', 'packinglists.color_id')
            ->whereNotNull('fabric_rolls.racked_at')
            ->whereNull('fabric_issuances.fabric_roll_id')
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
                'packinglists.gl_number',
                'packinglists.batch_number',
                'colors.color'
            );
        
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('checkbox', function ($row) {
                if($row->new_roll_id) { return null; }
                
                $checkbox_element = '
                    <div class="form-group mb-0">
                        <div class="custom-control custom-checkbox">
                            <input 
                                id="roll_checkbox_'. $row->id .'" 
                                name="selected_roll[]" 
                                class="custom-control-input checkbox-roll-control" 
                                type="checkbox" 
                                value="'. $row->id .'"
                                data-roll-number="'. $row->serial_number .'"
                                onchange="checkbox_clicked()" 
                            >
                            <label for="roll_checkbox_'. $row->id .'" class="custom-control-label"></label>
                        </div>
                    </div>
                ';
                return $checkbox_element;
            })
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="move_to_fbr(this)">Move to FBR</a>';
            })
            ->filter(function ($query){
                if (request('gl_filter')) {
                    $query->where('gl_number', request()->gl_filter)->get();
                }

                if (request('color_filter')) {
                    $query->where('color_id', request()->color_filter)->get();
                }
                    
                if (request('batch_filter')) {
                    $query->where('batch_number', request()->batch_filter)->get();
                }
                
            }, true)
            ->toJson();
    }

}
