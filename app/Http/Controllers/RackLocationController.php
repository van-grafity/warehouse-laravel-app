<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rack;
use App\Models\Location;
use App\Models\RackLocation;

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
                    <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="show_modal_edit(\'modal_change_rack_location\', '.$row->id.')">Edit</a>
                ';
                return $return; 
            })

            ->filter(function ($query) {
                if (request()->has('rack_location_filter')) {
                    if (request('rack_location_filter') == 'allocated') {
                        $query->where('rack_locations.location_id', '!=', null);
                    }
                    if (request('rack_location_filter') == 'unallocated') {
                        $query->where('rack_locations.location_id','=', null);
                    }
                }
            }, true)
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
                                data-rack-number="'. $row->id .'"
                                onchange="checkbox_clicked()" 
                            >
                            <label for="rack_checkbox_'. $row->id .'" class="custom-control-label"></label>
                        </div>
                    </div>
                ';
                return $checkbox_element;
            })
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

                foreach ($selected_rack_ids as $key => $rack_id) {
                    $rackLocation = RackLocation::where('rack_id', $rack_id)->first();

                    if ($rackLocation) {
                        $rackLocation->update(['location_id' => $location_id]);
                        $updated_racks[] = $rackLocation;
                    } else {                   
                        $data_rack = RackLocation::firstOrCreate([
                            'location_id' => $location_id,
                            'rack_id' => $rack_id,
                            'entry_at' => date('Y-m-d H:i:s')
                        ]);

                        $rack = Rack::find($rack_id);
                        $rack->save();
                        $inserted_rack[] = $rack;                  
                    }
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $rack_location = RackLocation::find($id);
            $rack_location->location_id = $request->location;
            $rack_location->save();
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully updated rack location ',
                'data' => $rack_location
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