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
     /**
     * Show Datatable Data.
     */
    public function dtable()
    {
        $query = RackLocation::query();
        
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                $return = '
                    <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="show_modal_edit(\'modal_rack_location\', '.$row->id.')">Edit</a>
                ';
                return $return; 
            })
            ->addColumn('rack', function($row){
                return $row->rack->serial_number;
            })
            ->addColumn('location', function($row){
                return $row->location->location;
            })
            ->filter(function ($query) {
                if (request()->has('rack_location_filter') && request('rack_location_filter')) {
                    $rack_location = request('rack_location_filter');
                    $query->where('rack_location', $rack_location);
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
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rack_location = RackLocation::firstOrCreate([
                'location_id' => $request->location,
            ]);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully added new rack location',
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
                'message' => 'Successfully updated rack location',
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

     /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $rack_location = rackLocation::find($id);
            $rack_location->delete();
            $data_return = [
                'status' => 'success',
                'data'=> $rack_location,
                'message'=> 'Rack Location successfully Deleted!',
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