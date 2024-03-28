<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Supplier;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Yajra\Datatables\Datatables;


class InvoiceController extends Controller
{
    public function __construct()
    {
        Gate::define('manage', function ($user) {
            return $user->hasPermissionTo('invoice.manage');
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::get();

        $data = [
            'title' => 'Invoice',
            'page_title' => 'Invoice',
            'suppliers' => $suppliers,
            'can_manage' => auth()->user()->can('manage'),
        ];
        return view('pages.invoice.index', $data);
    }

    /**
     * Show Datatable Data.
     */
    public function dtable()
    {
        $query = Invoice::query();
        
        return DataTables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                return '
                <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="show_modal_edit(\'modal_invoice\', '.$row->id.')">Edit</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="show_modal_delete('.$row->id.')">Delete</a>';
            })
            ->addColumn('supplier', function($row){
                return $row->supplier->supplier;
            })
            ->editColumn('incoming_date', function($row){
                $readable_datetime = Carbon::createFromFormat('Y-m-d', $row->incoming_date);
                $readable_datetime = $readable_datetime->format('d F Y');
                return $readable_datetime;
            })
            ->filter(function ($query) {
                if (request()->query('incoming_date_start_filter')) {
                    $query->where('incoming_date','>=', request()->incoming_date_start_filter);
                }
                if (request()->query('incoming_date_end_filter')) {
                    $query->where('incoming_date','<=', request()->incoming_date_end_filter);
                }
            }, true)
            ->toJson();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        try {
            $invoice = Invoice::firstOrCreate([
                'invoice_number' => $request->invoice_number,
                'container_number' => $request->container_number,
                'incoming_date' => $request->incoming_date_input,
                'supplier_id' => $request->supplier,
            ]);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully added new invoice (' . $invoice->invoice_number . ')',
                'data' => [
                    'invoice' => $invoice,
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
            $invoice = Invoice::find($id);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully get invoice (' . $invoice->invoice . ')',
                'data' => [
                    'invoice' => $invoice,
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
            $invoice = Invoice::find($id);
            $invoice->invoice_number = $request->invoice_number;
            $invoice->container_number = $request->container_number;
            $invoice->incoming_date = $request->incoming_date_input;
            $invoice->supplier_id = $request->supplier;
            $invoice->save();
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully updated invoice ('. $invoice->invoice_number .')',
                'data' => $invoice
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
            $invoice = Invoice::find($id);
            $invoice->delete();
            $data_return = [
                'status' => 'success',
                'data'=> $invoice,
                'message'=> 'Invoice '.$invoice->invoice.' successfully Deleted!',
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
