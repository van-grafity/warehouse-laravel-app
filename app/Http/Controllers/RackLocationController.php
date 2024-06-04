<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rack;
use App\Models\Location;
use App\Models\RackLocation;
use App\Models\FabricRollRack;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Yajra\Datatables\Datatables;

class RackLocationController extends Controller
{
           /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = Location::get();

        $data = [
            'title' => 'Rack Location',
            'page_title' => 'Rack Location List',
            'locations' => $locations,
            'can_manage' => auth()->user()->can('manage'),
        ];
        return view('pages.rack-location.index', $data);
    }

    public function dtable()
    {
        $query = Rack::leftJoin('rack_locations','rack_locations.rack_id','=','racks.id')
        ->leftJoin('locations','locations.id','=','rack_locations.location_id')
        ->select(
            'racks.id',
            'racks.serial_number',
            'locations.location as location',
        ); 

        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                $return = '
                    <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="show_modal_edit(\'modal_change_rack_location\',this)" data-rack-id="'.$row->id.'" data-rack-number="'.$row->serial_number.'">Edit</a>
                ';
                return $return; 
            })
            ->addColumn('checkbox', function ($row) {
                if($row->rack_location_id) { return null; }
                
                $checkbox_element = '
                    <div class="form-group mb-0">
                        <div class="custom-control custom-checkbox">
                            <input 
                                id="rack_checkbox_'. $row->id .'" 
                                name="selected_print[]" 
                                class="custom-control-input checkbox-rack-control" 
                                type="checkbox" 
                                value="'. $row->id .'"
                                data-rack-number="'. $row->serial_number .'"
                                onchange="checkbox_clicked()" 
                            >
                            <label for="rack_checkbox_'. $row->id .'" class="custom-control-label"></label>
                        </div>
                    </div>
                ';
                return $checkbox_element;
            })
            ->editColumn('location', function($row) {
                return $row->location ?? '-';
            })
            ->addColumn('gl_number', function($row) {
                $gl_numbers = FabricRollRack::getGlNumberByRackId($row->id);
                return $gl_numbers ? $gl_numbers : '-';
            })
            ->addColumn('color', function($row) {
                $colors = FabricRollRack::getColorByRackId($row->id);
                return $colors ? $colors : '-';
            })
            ->addColumn('total_roll', function($row) {
                $total_roll = FabricRollRack::getTotalRollByRackId($row->id);
                return $total_roll;
            })
            ->filter(function ($query) {
                if (request()->has('rack_allocation_filter')) {
                    if (request('rack_allocation_filter') == 'allocated') {
                        $query->where('rack_locations.location_id', '!=', null);
                    }
                    if (request('rack_allocation_filter') == 'unallocated') {
                        $query->where('rack_locations.location_id','=', null);
                    }
                }

                if (request()->has('rack_type_filter')) {
                    if (request('rack_type_filter') == 'moveable') {
                        $query->where('racks.rack_type', '=', 'moveable');
                    }
                    if (request('rack_type_filter') == 'fixed') {
                        $query->where('racks.rack_type','=', 'fixed');
                    }
                }
            }, true)
            ->toJson();
    }
    
    public function store(Request $request)
    {
        try {
            $location_id = $request->location;
            $location = Location::find($location_id);
            
            $selected_rack_ids = explode(',',$request->selected_rack_id);

            $updated_racks = [];
            DB::transaction(function () use ($selected_rack_ids, $location_id, &$updated_racks) {

                $rack_location = RackLocation::whereIn('rack_id', $selected_rack_ids)->delete();

                foreach ($selected_rack_ids as $key => $rack_id) {
                    $data_rack = RackLocation::firstOrCreate([
                        'location_id' => $location_id,
                        'rack_id' => $rack_id,
                        'entry_at' => date('Y-m-d H:i:s')
                    ]);                           
                    $rack = Rack::find($rack_id);
                    $rack->save();
                    $inserted_rack[] = $rack;
                }

            });

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully updated '. count($selected_rack_ids) .' Rack locations to ' . $location->location,
                'data' => [
                    'updated_racks' => $updated_racks
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $rack_location = RackLocation::find($id);
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully get Rack location',
                'data' => [
                    'rack_location' => $rack_location,
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
}