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


class FabricOffloadingController extends Controller
{
    public function __construct()
    {
        Gate::define('manage', function ($user) {
            return $user->hasPermissionTo('fabric-offloading.manage');
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Fabric Offloading',
            'page_title' => 'Fabric Offloading',
            'can_manage' => auth()->user()->can('manage'),
        ];
        return view('pages.fabric-offloading.index', $data);
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
            ->addColumn('action', function ($row) {
                $action_button = "
                    <a href='" . route('fabric-offloading.detail', $row->id) . "' class='btn btn-primary btn-sm' >Offloading</a>
                ";
                return $action_button;
            })
            ->addColumn('serial_number', function ($row) {
                $serial_number = "<a href='" . route('packinglist.detail', $row->id) . "' class='' data-toggle='tooltip' data-placement='top' title='Click for Detail'>$row->serial_number</a>";
                return $serial_number;
            })
            ->addColumn('invoice', function ($row) {
                return $row->invoice->invoice_number;
            })
            ->addColumn('color', function ($row) {
                return $row->color->color;
            })
            ->addColumn('roll_qty', function ($row) {
                return $row->fabric_rolls->count();
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
            'title' => 'Fabric Offload & Racking',
            'page_title' => 'Fabric Offload & Racking',
            'packinglist' => $packinglist,
        ];
        return view('pages.fabric-offloading.detail', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rack_id = $request->rack;
            $rack = Rack::find($rack_id);
            $selected_roll_ids = explode(',', $request->selected_roll_id);
            $offloaded_roll = [];

            DB::transaction(function () use ($selected_roll_ids, $rack_id, &$offloaded_roll) {

                foreach ($selected_roll_ids as $key => $roll_id) {
                    $data_roll = FabricRollRack::firstOrCreate([
                        'rack_id' => $rack_id,
                        'fabric_roll_id' => $roll_id,
                        'stock_in_at' => date('Y-m-d H:i:s'),
                        'stock_in_by' => auth()->user()->id
                    ]);

                    $roll = FabricRoll::find($roll_id);
                    $roll->offloaded_at = date('Y-m-d H:i:s');
                    $roll->offloaded_by = auth()->user()->id;
                    $roll->racked_at = date('Y-m-d H:i:s');
                    $roll->racked_by = auth()->user()->id;
                    $roll->save();
                    $offloaded_roll[] = $roll;
                }
            });

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully Offloaded ' . count($offloaded_roll) . ' Roll',
                'data' => [
                    'offloaded_roll' => $offloaded_roll
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
                'fabric_rolls.offloaded_at',
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
            ->addColumn('action', function ($row) {
                $action_button = "
                    <a href='javascript:void(0)' class='btn btn-primary btn-sm' onclick='show_modal_edit(\"modal_fabric_roll\", $row->id)' >Edit</a>
                    <a href='javascript:void(0)' class='btn btn-danger btn-sm'  onclick='show_modal_delete($row->id)'>Delete</a>
                ";
                return $action_button;
            })
            ->addColumn('checkbox', function ($row) {
                if ($row->offloaded_at) {
                    $checkbox_element = '
                        <div class="form-group mb-0" data-toggle="tooltip" data-placement="top" title="Loaded">
                            <div class="custom-control custom-checkbox">
                                <input 
                                    id="roll_checkbox_' . $row->id . '" 
                                    name="selected_roll[]" 
                                    class="custom-control-input checkbox-roll-control" 
                                    type="checkbox" 
                                    value="' . $row->id . '"
                                    data-roll-number="' . $row->roll_number . '"
                                    checked="checked"
                                    disabled
                                >
                                <label for="roll_checkbox_' . $row->id . '" class="custom-control-label"></label>
                            </div>
                        </div>
                    ';
                } else {
                    $checkbox_element = '
                        <div class="form-group mb-0">
                            <div class="custom-control custom-checkbox">
                                <input 
                                    id="roll_checkbox_' . $row->id . '" 
                                    name="selected_roll[]" 
                                    class="custom-control-input checkbox-roll-control" 
                                    type="checkbox" 
                                    value="' . $row->id . '"
                                    data-roll-number="' . $row->roll_number . '"
                                    onchange="checkbox_clicked()"
                                >
                                <label for="roll_checkbox_' . $row->id . '" class="custom-control-label"></label>
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
