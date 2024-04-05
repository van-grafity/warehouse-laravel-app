<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LocationRow;
use App\Models\Location;

use Illuminate\Support\Facades\Gate;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;

class LocationRowController extends Controller
{

/**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Row',
            'page_title' => 'Row List',
            'can_manage' => auth()->user()->can('manage'),
        ];
        return view('pages.location-row.index', $data);
    }

     /**
     * Show Datatable Data.
     */
    public function dtable()
    {
        $query = LocationRow::get();
        
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                $return = '
                    <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="show_modal_edit(\'modal_location_row\', '.$row->id.')">Edit</a>
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
            $row = LocationRow::firstOrCreate([
                'row' => $request->row,
                'description' => $request->description,
            ]);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully added new row (' . $row->row . ')',
                'data' => [
                    'row' => $row,
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
            $row = LocationRow::find($id);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully added new row (' . $row->row . ')',
                'data' => [
                    'row' => $row,
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
            $row = LocationRow::find($id);
            $row->row = $request->row;
            $row->description = $request->description;
            $row->save();
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully updated row ('. $row->row .')',
                'data' => $row
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
            $row = LocationRow::find($id);
            $is_location_exists = Location::where('location_row_id', $id)->exists();

            // ## Periksa apakah ada location yang menggunakan row dengan id yang diberikan
            if ($is_location_exists){
                $data_return = [
                    'status' => 'error',
                    'message' => 'Failed to delete row '.$row->row.', because this row has been used on location!'
                ];
            } else {
                $row->delete();
                $data_return = [
                    'status' => 'success',
                    'data'=> $row,
                    'message'=> 'Row '.$row->row.' successfully Deleted!',
                ];
            }

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

