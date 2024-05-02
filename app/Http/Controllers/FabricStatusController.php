<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Packinglist;
use App\Models\FabricRoll;
use App\Models\FabricRollRack;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Yajra\Datatables\Datatables;

use App\Exports\InstoreReportExport;
use Maatwebsite\Excel\Facades\Excel;

class FabricStatusController extends Controller
{
 public function __construct()
    {
        Gate::define('manage', function ($user) {
            return $user->hasPermissionTo('fabric-status.manage');
        });
        Gate::define('print', function ($user) {
            return $user->hasPermissionTo('instore-report.print');
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Fabric Status',
            'page_title' => 'Fabric Status',
            'can_manage' => auth()->user()->can('manage'),
        ];
        return view('pages.fabric-status.index', $data);
    }

    /**
     * Show Datatable Data.
     */
    public function dtable()
    {
        $query = Packinglist::query();
        
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                $action_button = "
                    <a href='". route('fabric-status.detail',$row->id)."' class='btn btn-primary btn-sm' >Detail</a>
                ";
                return $action_button;
            })
            ->addColumn('serial_number', function($row){
                $serial_number = "<a href='". route('packinglist.detail',$row->id)."' class='' data-toggle='tooltip' data-placement='top' title='Click for Detail'>$row->serial_number</a>";
                return $serial_number;
            })
            ->addColumn('invoice', function($row){
                return $row->invoice->invoice_number;
            })
            ->addColumn('color', function($row){
                return $row->color->color;
            })
            ->addColumn('total_length_yds', function($row){
                $PackinglistModel = new Packinglist;
                $total_length_yds = $PackinglistModel->getRollSummaryInPackinglist($row->id,'stock_in');
                return ($total_length_yds ? $total_length_yds->total_length_yds : 0) . ' yds';
            })
            ->addColumn('total_weight_kgs', function($row){
                $PackinglistModel = new Packinglist;
                $total_weight_kgs = $PackinglistModel->getRollSummaryInPackinglist($row->id,'stock_in');
                return ($total_weight_kgs ? $total_weight_kgs->total_weight_kgs : 0) . ' kgs';
            })
            ->addColumn('roll_balance', function($row){
                $PackinglistModel = new Packinglist;
                $stock_in_roll = $PackinglistModel->getRollSummaryInPackinglist($row->id,'stock_in');
                return $stock_in_roll ? $stock_in_roll->total_roll : 0;
            })

            ->toJson();
    }

    /**
     * Show detail page of resource.
     */
    public function detail(string $id)
    {
        $packinglist = Packinglist::find($id);
        $data = [
            'title' => 'Fabric Status',
            'page_title' => 'Fabric Status',
            'packinglist' => $packinglist,
        ];
        return view('pages.fabric-status.detail', $data);
    }

    /**
     * Show Datatable Data.
     */
    public function dtable_roll_list()
    {
        $packinglist_id = request()->packinglist_id;

        $query = FabricRoll::leftJoin('fabric_roll_racks','fabric_roll_racks.fabric_roll_id','=','fabric_rolls.id')
            ->leftJoin('racks','racks.id','=','fabric_roll_racks.rack_id')
            ->where('fabric_rolls.packinglist_id', $packinglist_id)
            ->where('fabric_rolls.racked_by','!=', null)
            ->select(
                'fabric_rolls.id', 
                'fabric_rolls.roll_number', 
                'fabric_rolls.serial_number', 
                'fabric_rolls.kgs', 
                'fabric_rolls.lbs', 
                'fabric_rolls.yds', 
                'racks.serial_number as rack_number'
            )
            ->get();
        
        return Datatables::of($query)
            ->make(true);
    }
    
    public function export(Request $request)
    {          
        $query = Packinglist::query();
        $packinglist = Packinglist::find($request->id);
        dd ($packinglist);

        // $PackinglistModel = new Packinglist;
        // $roll_summary_in_packinglist = $PackinglistModel->getRollSummaryInPackinglist($packinglist->id);
        // $stock_in_summary = $PackinglistModel->getRollSummaryInPackinglist($packinglist->id, 'stock_in');
        return Excel::download(new InstoreReportExport, 'Instore-Report.xlsx');
    }
}