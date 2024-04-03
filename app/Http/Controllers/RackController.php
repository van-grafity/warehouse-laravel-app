<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rack;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class RackController extends Controller
{

    public function __construct()
    {
        Gate::define('manage', function ($user) {
            return $user->hasPermissionTo('rack.manage');
        });
    }

        /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Rack',
            'page_title' => 'Rack List',
            'can_manage' => auth()->user()->can('manage'),
        ];
        return view('pages.rack.index', $data);
    }

     /**
     * Show Datatable Data.
     */
    public function dtable()
    {
        $query = Rack::get();
        
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                $return = '
                    <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="show_modal_edit(\'modal_rack\', '.$row->id.')">Edit</a>
                    <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="show_modal_delete('.$row->id.')">Delete</a>
                ';
                return $return; 
            })
            ->make(true);
    }

        /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rack = Rack::firstOrCreate([
                'rack' => $request->rack,
                'description' => $request->description,
            ]);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully added new rack (' . $rack->rack . ')',
                'data' => [
                    'rack' => $rack,
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
            $rack = Rack::find($id);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully added new rack (' . $rack->rack . ')',
                'data' => [
                    'rack' => $rack,
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
            $rack = Rack::find($id);
            $rack->rack = $request->rack;
            $rack->description = $request->description;
            $rack->save();
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully updated rack ('. $rack->rack .')',
                'data' => $rack
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
            $rack = Rack::find($id);
            $rack->delete();
            $data_return = [
                'status' => 'success',
                'data'=> $rack,
                'message'=> 'Rack '.$rack->rack.' successfully Deleted!',
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
