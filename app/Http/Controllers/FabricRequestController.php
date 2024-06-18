<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApiFabricRequest;
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
        $query = FabricRequest::with('apiFabricRequest')
            ->select([
                'fabric_requests.*',
                'api_fabric_requests.fbr_serial_number',
                'api_fabric_requests.fbr_gl_number',
                'api_fabric_requests.fbr_color',
                'api_fabric_requests.fbr_table_number',
                'api_fabric_requests.fbr_qty_required',
                'api_fabric_requests.fbr_requested_at'
            ])
            ->leftJoin('api_fabric_requests', 'fabric_requests.api_fabric_request_id', '=', 'api_fabric_requests.id');
        
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                $action_button = "
                    <a href='". route('fabric-request.issue-fabric',$row->id)."' class='btn btn-primary btn-sm'>Issue Fabric</a>";
                return $action_button;
            })
            ->addColumn('serial_number', function($row){
                $serial_number = $row->apiFabricRequest ? $row->apiFabricRequest->fbr_serial_number : '';
                $serial_number_link = "<a href='". route('fabric-request.detail',$row->id)."' class='' data-toggle='tooltip' data-placement='top' title='Click for Detail'>$serial_number</a>";
                return $serial_number_link;
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
     * Synchronize data with Cutting App.
     */
    public function sync(Request $request)
    {
        try {
            $fabric_requests = $request->fabric_requests;
            $fbr_counter = 0;

            DB::beginTransaction();
            
            foreach ($fabric_requests as $key_fbr => $fabric_request) {
                $fabric_request = (object) $fabric_request;

                // Check for existing API fabric request
                $api_fabric_request = ApiFabricRequest::firstOrCreate(
                    ['fbr_id' => $fabric_request->fbr_id],
                    [
                        'fbr_serial_number' => $fabric_request->fbr_serial_number,
                        'fbr_status_print' => $fabric_request->fbr_status_print,
                        'fbr_remark' => $fabric_request->fbr_remark,
                        'fbr_requested_at' => $fabric_request->fbr_requested_at,
                        'fbr_requested_by' => $fabric_request->fbr_requested_by,
                        'fbr_created_at' => $fabric_request->fbr_created_at,
                        'fbr_updated_at' => $fabric_request->fbr_updated_at,
                        'fbr_laying_planning_id' => $fabric_request->laying_planning_id,
                        'fbr_laying_planning_serial_number' => $fabric_request->laying_planning_serial_number,
                        'fbr_style' => $fabric_request->style,
                        'fbr_fabric_type' => $fabric_request->fabric_type,
                        'fbr_fabric_po' => $fabric_request->fabric_po,
                        'fbr_laying_planning_detail_id' => $fabric_request->laying_planning_detail_id,
                        'fbr_gl_number' => $fabric_request->gl_number,
                        'fbr_color' => $fabric_request->color,
                        'fbr_table_number' => $fabric_request->table_number,
                        'fbr_qty_required' => $fabric_request->qty_required,
                    ]
                );

                // Prepare data for fabric_requests table
                $fabric_request_data = [
                    'api_fabric_request_id' => $api_fabric_request->id,
                    'last_sync_by' => auth()->user()->id,
                    'last_sync_at' => now(),
                ];

                // Check if fabric request exists and update or create
                FabricRequest::updateOrCreate(
                    ['api_fabric_request_id' => $api_fabric_request->id],
                    $fabric_request_data
                );

                $fbr_counter++;
            }

            DB::commit();

            $data_return = [
                'status' => 'success',
                'message' => "Successfully Sync {$fbr_counter} Fabric Requests",
                'data' => [
                    'fabric_requests' => $fabric_requests,
                ]
            ];
            return response()->json($data_return);

        } catch (\Throwable $th) {
            DB::rollback();
            
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
        
        $allocated_fabric_roll = $allocated_fabric_roll->map(function ($fabric_roll) {
            return [
                'id' => $fabric_roll->id,
                'serial_number' => $fabric_roll->serial_number,
                'roll_number' => $fabric_roll->roll_number,
                'width' => $fabric_roll->width,
                'yds' => $fabric_roll->yds,
                'color' => $fabric_roll->packinglist->color->color,
                'batch' => $fabric_roll->packinglist->batch_number,
                'rack_number' => $fabric_roll->rack->serial_number,
                'location' => $fabric_roll->rack->location->location,
            ];
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
            DB::beginTransaction();

            $currentTimestamp = Carbon::now();
            $userId = auth()->user()->id;
            
            $fabric_request = FabricRequest::findOrFail($fbr_id);
            $new_fabric_roll_ids = collect($request->confirmed_fabric_roll); // ## collect to ensure is in array
            
            $old_fabric_rolls = $fabric_request->allocatedFabricRolls()->pluck('fabric_roll_id');
            
            // ## Determine the fabric rolls to delete
            $fabric_rolls_to_delete = $old_fabric_rolls->diff($new_fabric_roll_ids);
            
            // ## Determine the fabric rolls to add
            $fabric_rolls_to_add = $new_fabric_roll_ids->diff($old_fabric_rolls);

            // ## Delete fabric rolls that are not in the new list
            if ($fabric_rolls_to_delete->isNotEmpty()) {
                $fabric_request->allocatedFabricRolls()->detach($fabric_rolls_to_delete);
            }

            // ## Add new fabric rolls that are not in the old list
            if ($fabric_rolls_to_add->isNotEmpty()) {
                
                
                // ## using map for manual add UserRecord because using attach
                $attachData = $fabric_rolls_to_add->mapWithKeys(function ($fabric_roll_id) use ($currentTimestamp, $userId) {
                    return [
                        $fabric_roll_id => [
                            'created_at' => $currentTimestamp,
                            'updated_at' => $currentTimestamp,
                            'created_by' => $userId,
                            'updated_by' => $userId
                        ]
                    ];
                })->toArray();
                $fabric_request->allocatedFabricRolls()->attach($attachData);
            }
            
            // ## Update the issued_at timestamp of the fabric request
            $fabric_request->issued_at = $currentTimestamp;
            $fabric_request->save();

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully allocated fabric rolls to ' . $fabric_request->fbr_serial_number,
                'data' => [
                    'fabric_request' => $fabric_request,
                    'new_fabric_rolls' => $new_fabric_roll_ids,
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
