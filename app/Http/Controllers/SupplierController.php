<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Invoice;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class SupplierController extends Controller
{
    public function __construct()
    {
        Gate::define('manage', function ($user) {
            return $user->hasPermissionTo('supplier.manage');
        });
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Supplier',
            'page_title' => 'Supplier List',
            'can_manage' => auth()->user()->can('manage'),
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
            ->addColumn('action', function($row){
                return '
                <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="show_modal_edit(\'modal_supplier\', '.$row->id.')">Edit</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="show_modal_delete('.$row->id.')">Delete</a>';
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
                'description' => $request->description,
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
            $supplier->description = $request->description;

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
            $is_invoice_exists = Invoice::where('supplier_id', $id)->exists();
            
            // ##Periksa apakah ada invoice yang menggunakan supplier dengan id yang diberikan
            if ($is_invoice_exists){
                $data_return = [
                    'status' => 'error',
                    'message' => 'Failed to delete supplier '.$supplier->supplier.', because this supplier has been used on invoice!'
                ];
            } else {
                $supplier->delete();
                $data_return = [
                    'status' => 'success',
                    'data'=> $supplier,
                    'message'=> 'Supplier '.$supplier->supplier.' successfully Deleted!',
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
