<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Packinglist;
use App\Models\ApiFabricRequest;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

use Yajra\Datatables\Datatables;

use App\Exports\FabricStatusReportsExport;
use Maatwebsite\Excel\Facades\Excel;

class FabricStatusReportController extends Controller
{
 public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $data = [
            'title' => 'Fabric Status Report',
            'page_title' => 'Fabric Status Report',
        ];
        return view('pages.fabric-status-report.index', $data);
    }

    public function dtable_preview()
    {
        $query = Packinglist::join('invoices','invoices.id','=','packinglists.invoice_id')
            ->join('suppliers','suppliers.id','=','invoices.supplier_id')
            ->join('colors','colors.id','=','packinglists.color_id')
            ->select([
                'invoices.incoming_date',
                'suppliers.supplier',
                'invoices.invoice_number',
                'packinglists.buyer',
                'invoices.container_number',
                'packinglists.batch_number',
                'packinglists.gl_number',
                'packinglists.po_number',
                'packinglists.style',
                'packinglists.fabric_content',
                'colors.color',
            ]);
        
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->filter(function ($query) {
                if (request()->query('date_start_filter')) {
                    $query->where('invoices.incoming_date','>=', request()->date_start_filter);
                }
                if (request()->query('date_end_filter')) {
                    $query->where('invoices.incoming_date','<=', request()->date_end_filter);
                }

            }, true)
            ->toJson();
    }

    public function export_excel(Request $request)
    {
        $startDate = $request->date_start_filter ?? Carbon::now()->subMonth()->startOfMonth();
        $endDate = $request->date_end_filter ?? Carbon::now()->addMonth()->startOfMonth();

        return (new FabricStatusReportsExport)->startDateFilter($startDate)->endDateFilter($endDate)->download();
    }

}