<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rack;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Yajra\Datatables\Datatables;
use PDF;

class RackController extends Controller
{

    public function __construct()
    {
        Gate::define('manage', function ($user) {
            return $user->hasPermissionTo('rack.manage');
        });
        Gate::define('print', function ($user) {
            return $user->hasPermissionTo('rack.print-barcode');
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
            'can_print' => auth()->user()->can('print'),
        ];
        return view('pages.rack.index', $data);
    }

     /**
     * Show Datatable Data.
     */
    public function dtable()
    {
        $query = Rack::query();
        
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
            ->editColumn('basic_number', function($row){
                return normalizeNumber($row->basic_number,2);
            })
            ->editColumn('rack_type', function($row){
                return Str::ucfirst($row->rack_type);
            })         
            ->filter(function ($query) {
                if (request()->has('rack_type_filter') && request('rack_type_filter')) {
                    $rack_type = request('rack_type_filter');
                    $query->where('rack_type', $rack_type);
                }
            }, true)
            ->addColumn('checkbox', function ($row) {
                if($row->print_rack_id) { return null; }
                
                $checkbox_element = '
                    <div class="form-group mb-0">
                        <div class="custom-control custom-checkbox">
                            <input 
                                id="print_checkbox_'. $row->id .'" 
                                name="selected_print[]" 
                                class="custom-control-input checkbox-print-control" 
                                type="checkbox" 
                                value="'. $row->id .'"
                                onchange="checkbox_clicked()" 
                            >
                            <label for="print_checkbox_'. $row->id .'" class="custom-control-label"></label>
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
            $rack = Rack::firstOrCreate([
                'basic_number' => $request->basic_number,
                'rack_type' => $request->rack_type,
                'description' => $request->description,
            ]);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully added new rack (' . $rack->serial_number . ')',
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
            $rack->basic_number = $request->basic_number;
            $rack->rack_type = $request->rack_type;
            $rack->description = $request->description;
            $rack->save();
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully updated rack ('. $rack->serial_number .')',
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

    // ## Print Barcode
    public function print_barcode(Request $request)
    {
        $id = explode(',', $request->id);
        $racks = Rack::select('id', 'serial_number', 'basic_number')->whereIn('id', $id)->get();
        
        $data = [
            'racks' => $racks,
        ];

        $pdf = PDF::loadview('pages.rack.print-barcode', $data)->setPaper(array(0, 0, 595.28, 935.43), 'potrait');
        return $pdf->stream('rack-serial-number.pdf');
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
                'message'=> 'Rack '.$rack->serial_number.' successfully Deleted!',
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
