<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\LocationRow;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class LocationController extends Controller
{

    public function __construct()
    {
        Gate::define('manage', function ($user) {
            return $user->hasPermissionTo('location.manage');
        });
    }

        /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $location_rows = LocationRow::get();

        $data = [
            'title' => 'Location',
            'page_title' => 'Location List',
            'location_rows' => $location_rows,
            'can_manage' => auth()->user()->can('manage'),
        ];
        return view('pages.location.index', $data);
    }

     /**
     * Show Datatable Data.
     */
    public function dtable()
    {
        $query = Location::get();
        
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                return '
                <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="show_modal_edit(\'modal_location\', '.$row->id.')">Edit</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="show_modal_delete('.$row->id.')">Delete</a>';
            })
            ->addColumn('location_row', function($row){
                return $row->location_row->row;
            })
            ->make(true);
    }

        /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $location = Location::firstOrCreate([
                'location' => $request->location,
                'description' => $request->description,
                'location_row_id' => $request->location_row,
            ]);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully added new location (' . $location->location . ')',
                'data' => [
                    'location' => $location,
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
            $location = Location::find($id);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully get location (' . $location->location . ')',
                'data' => [
                    'location' => $location,
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
            $location = Location::find($id);
            $location->location = $request->location;
            $location->description = $request->description;
            $location->location_row_id = $request->location_row;
            $location->save();
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully updated location ('. $location->location .')',
                'data' => $location
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
            $location = Location::find($id);
            $location->delete();
            $data_return = [
                'status' => 'success',
                'data'=> $location,
                'message'=> 'Location '.$location->location.' successfully Deleted!',
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
