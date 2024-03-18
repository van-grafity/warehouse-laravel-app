<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Supplier',
            'page_title' => 'Supplier List'
        ];
        return view('pages.supplier.index', $data);
    }

    /**
     * Show Datatable Data.
     */
    public function dtable()
    {
        $query = Supplier::get();
        
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($data){
                return '
                <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="show_modal_edit(\'modal_supplier\', '.$data->id.')">Edit</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="show_modal_delete('.$data->id.')">Delete</a>';
            })
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $supplier = Supplier::firstOrCreate([
                'supplier' => $request->supplier,
            ]);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully added new supplier (' . $supplier->supplier . ')',
                'data' => [
                    'supplier' => $supplier,
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
            $supplier = Supplier::find($id);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully get supplier (' . $supplier->supplier . ')',
                'data' => [
                    'supplier' => $supplier,
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
            $supplier = Supplier::find($id);
            $supplier->supplier = $request->supplier;
            $supplier->save();
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully updated supplier ('. $supplier->supplier .')',
                'data' => $supplier
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
            $supplier = Supplier::find($id);
            $supplier->delete();
            $data_return = [
                'status' => 'success',
                'data'=> $supplier,
                'message'=> 'Supplier '.$supplier->supplier.' successfully Deleted!',
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
