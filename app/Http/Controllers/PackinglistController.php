<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Packinglist;
use App\Models\Supplier;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Yajra\Datatables\Datatables;


class PackinglistController extends Controller
{
    public function __construct()
    {
        Gate::define('manage', function ($user) {
            return $user->hasPermissionTo('packinglist.manage');
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::get();

        $data = [
            'title' => 'Packinglist',
            'page_title' => 'Packinglist',
            'suppliers' => $suppliers,
            'can_manage' => auth()->user()->can('manage'),
        ];
        return view('pages.packinglist.index', $data);
    }

    /**
     * Show Datatable Data.
     */
    public function dtable()
    {
        $query = Packinglist::query();
        
        return DataTables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                return '
                <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="show_modal_edit(\'modal_packinglist\', '.$row->id.')">Edit</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="show_modal_delete('.$row->id.')">Delete</a>';
            })
            ->addColumn('invoice', function($row){
                return $row->invoice->invoice_number;
            })
            ->addColumn('color', function($row){
                return $row->color->color;
            })
            ->addColumn('roll_qty', function($row){
                // $roll_qty = $this->FabricRollModel->where('packinglist_id', $row->id)->findAll();
                // return count($roll_qty);
                return 0;
            })
            ->toJson();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        try {
            $packinglist = Packinglist::firstOrCreate([
                'packinglist_number' => $request->packinglist_number,
                'container_number' => $request->container_number,
                'incoming_date' => $request->incoming_date_input,
                'supplier_id' => $request->supplier,
            ]);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully added new packinglist (' . $packinglist->packinglist_number . ')',
                'data' => [
                    'packinglist' => $packinglist,
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
            $packinglist = Packinglist::find($id);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully get packinglist (' . $packinglist->packinglist . ')',
                'data' => [
                    'packinglist' => $packinglist,
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
            $packinglist = Packinglist::find($id);
            $packinglist->packinglist_number = $request->packinglist_number;
            $packinglist->container_number = $request->container_number;
            $packinglist->incoming_date = $request->incoming_date_input;
            $packinglist->supplier_id = $request->supplier;
            $packinglist->save();
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully updated packinglist ('. $packinglist->packinglist_number .')',
                'data' => $packinglist
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
            $packinglist = Packinglist::find($id);
            $packinglist->delete();
            $data_return = [
                'status' => 'success',
                'data'=> $packinglist,
                'message'=> 'Packinglist '.$packinglist->packinglist.' successfully Deleted!',
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
