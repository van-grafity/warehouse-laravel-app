<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PoFabric;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Yajra\Datatables\Datatables;

class FabricPoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Fabric PO',
            'page_title' => 'Fabrci PO List',
            'can_manage' => auth()->user()->can('manage'),
        ];
        return view('pages.fabric-po.index', $data);
    }

    /**
     * Show Datatable Data.
     */
    public function dtable()
    {
        $query = PoFabric::get();
        
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                return '
                <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="show_modal_edit(\'modal_fabric_po\', '.$row->id.')">Edit</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="show_modal_delete('.$row->id.')">Delete</a>';
            })
            ->editColumn('po_date', function($row){
                $readable_datetime = Carbon::createFromFormat('Y-m-d', $row->po_date);
                $readable_datetime = $readable_datetime->format('d F Y');
                return $readable_datetime;
            })
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $fabric_po = PoFabric::firstOrCreate([
                'fabric_po' => $request->fabric_po,
                'po_date' => $request->po_date_input,
            ]);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully added new fabric po (' . $fabric_po->fabric_po . ')',
                'data' => [
                    'fabric_po' => $fabric_po,
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
            $fabric_po = PoFabric::find($id);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully get fabric po (' . $fabric_po->fabric_po . ')',
                'data' => [
                    'fabric_po' => $fabric_po,
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
            $fabric_po = PoFabric::find($id);
            $fabric_po->fabric_po = $request->fabric_po;
            $fabric_po->po_date = $request->po_date_input;
            $fabric_po->save();
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully updated fabric po ('. $fabric_po->fabric_po .')',
                'data' => $fabric_po
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
            $fabric_po = PoFabric::find($id);
            $fabric_po->delete();
            $data_return = [
                'status' => 'success',
                'data'=> $fabric_po,
                'message'=> 'Fabric PO '.$fabric_po->fabric_po.' successfully Deleted!',
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
