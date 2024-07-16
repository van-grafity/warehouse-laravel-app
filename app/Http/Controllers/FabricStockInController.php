<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FabricRoll;
use App\Models\FabricRollRack;
use App\Models\Packinglist;
use App\Models\Rack;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Yajra\Datatables\Datatables;


class FabricStockInController extends Controller
{
    public function __construct()
    {
        Gate::define('manage', function ($user) {
            return $user->hasPermissionTo('fabric-stock-in.manage');
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packinglist = Packinglist::select('gl_number')->distinct()->get();

        $data = [
            'title' => 'Fabric Stock In',
            'page_title' => 'Fabric Stock In',
            'packinglist' => $packinglist,
            'can_manage' => auth()->user()->can('manage'),
        ];
        return view('pages.fabric-stock-in.index', $data);
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
                    <a href='". route('fabric-stock-in.detail',$row->id)."' class='btn btn-primary btn-sm' >Stock in</a>
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
            ->addColumn('roll_balance', function($row){
                $PackinglistModel = new Packinglist;
                $stock_in_roll = $PackinglistModel->getRollSummaryInPackinglist($row->id,'stock_in');
                return $stock_in_roll ? $stock_in_roll->total_roll : 0;
            })

            ->filter(function ($query, $gl_filter){
                if (request('gl_filter')) {
                    $query->where('gl_number', request()->gl_filter);
                }
                if (request('color_filter')) {
                    $query->where('color_id', request()->color_filter);
                }
                if (request('invoice_filter')) {
                    $query->where('invoice_id', request()->invoice_filter);
                }
            }, true)
            ->toJson();
    }

    /**
     * Show detail page of resource.
     */
    public function detail(string $id)
    {
        $packinglist = Packinglist::find($id);
        $data = [
            'title' => 'Fabric Stock In',
            'page_title' => 'Fabric Stock In',
            'packinglist' => $packinglist,
        ];
        return view('pages.fabric-stock-in.detail', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rack_id = $request->rack;
            $rack = Rack::find($rack_id);
            $selected_roll_ids = explode(',',$request->selected_roll_id);
            
            $inserted_roll = [];
            DB::transaction(function () use ($selected_roll_ids, $rack_id, &$inserted_roll) {

                foreach ($selected_roll_ids as $key => $roll_id) {
                    $data_roll = FabricRollRack::firstOrCreate([
                        'rack_id' => $rack_id,
                        'fabric_roll_id' => $roll_id,
                        'stock_in_at' => date('Y-m-d H:i:s'),
                        'stock_in_by' => auth()->user()->id
                    ]);

                    $roll = FabricRoll::find($roll_id);
                    $roll->racked_at = date('Y-m-d H:i:s');
                    $roll->racked_by = auth()->user()->id;
                    $roll->save();
                    $inserted_roll[] = $roll;
                }
            });

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully '. count($inserted_roll) .' Roll to ' . $rack->serial_number,
                'data' => [
                    'inserted_roll' => $inserted_roll
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
     * Show Datatable Data.
     */
    public function dtable_roll_list()
    {
        $packinglist_id = request()->packinglist_id;

        $query = FabricRoll::leftJoin('fabric_roll_racks','fabric_roll_racks.fabric_roll_id','=','fabric_rolls.id')
            ->leftJoin('racks','racks.id','=','fabric_roll_racks.rack_id')
            ->where('fabric_rolls.packinglist_id', $packinglist_id)
            ->select(
                'fabric_rolls.id', 
                'fabric_rolls.roll_number', 
                'fabric_rolls.serial_number', 
                'fabric_rolls.kgs', 
                'fabric_rolls.lbs', 
                'fabric_rolls.yds',
                'fabric_rolls.width',  
                'fabric_rolls.racked_at',
                'racks.serial_number as rack_number'
            );


        // ## penambahan logika sorting agar mampu sort string as number
        $orderData = request()->input('order');

        //##  Cek apakah ada data order dan memenuhi kondisi yang dibutuhkan
        if (!empty($orderData) && isset($orderData[0]['column'], $orderData[0]['dir'])) {
            $orderIndex = $orderData[0]['column'];
            $dir = $orderData[0]['dir'];

            // ## Pengurutan berdasarkan kolom yang diurutkan, dalam hal ini roll_number berada di index 1
            if ($orderIndex == 1) {
                $query->orderByRaw("CAST(fabric_rolls.roll_number AS UNSIGNED) $dir");
            }
        }
        
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                $action_button = "
                    <a href='javascript:void(0)' class='btn btn-primary btn-sm' onclick='show_modal_edit(\"modal_fabric_roll\", $row->id)' >Edit</a>
                    <a href='javascript:void(0)' class='btn btn-danger btn-sm'  onclick='show_modal_delete($row->id)'>Delete</a>
                ";
                return $action_button;
            })
            ->editColumn('lbs', function($row) {
                return $row->lbs ?? '-';
            })
            ->addColumn('checkbox', function ($row) {
                $FabricRollRackModel = new FabricRollRack;
                $roll_rack = $FabricRollRackModel->where('fabric_roll_id', $row->id)->first();
                if($roll_rack) {
                    $checkbox_element = '
                        <div class="form-group mb-0">
                            <div class="custom-control custom-checkbox">
                                <input 
                                    id="roll_checkbox_'. $row->id .'" 
                                    name="selected_roll[]" 
                                    class="custom-control-input checkbox-roll-control" 
                                    type="checkbox" 
                                    value="'. $row->id .'"
                                    data-roll-number="'. $row->roll_number .'"
                                    checked="checked"
                                    disabled
                                >
                                <label for="roll_checkbox_'. $row->id .'" class="custom-control-label"></label>
                            </div>
                        </div>
                    ';

                } else {
                    $checkbox_element = '
                        <div class="form-group mb-0">
                            <div class="custom-control custom-checkbox">
                                <input 
                                    id="roll_checkbox_'. $row->id .'" 
                                    name="selected_roll[]" 
                                    class="custom-control-input checkbox-roll-control" 
                                    type="checkbox" 
                                    value="'. $row->id .'"
                                    data-roll-number="'. $row->roll_number .'"
                                    onchange="checkbox_clicked()"
                                >
                                <label for="roll_checkbox_'. $row->id .'" class="custom-control-label"></label>
                            </div>
                        </div>
                    ';
                }
                
                return $checkbox_element;
            })
            ->editColumn('racked_at', function($row){
                if(!$row->racked_at) { return null; }
                $racked_at = Carbon::createFromFormat('Y-m-d H:i:s', $row->racked_at);
                $readable_racked_at = $racked_at->format('d F Y, H:i');
                return $readable_racked_at;
            })
            ->toJson();
    }
}
