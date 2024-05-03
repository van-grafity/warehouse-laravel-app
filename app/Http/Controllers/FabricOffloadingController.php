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
            'title' => 'Roll Loading',
            'page_title' => 'Roll Loading',
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
            $selected_roll_ids = explode(',', $request->selected_roll_id);
            $offloaded_roll = [];
            foreach ($selected_roll_ids as $key => $roll_id) {
                $data_update = [
                    'offloaded_at' => Carbon::now(),
                    'offloaded_by' => auth()->user()->id,
                ];
                $roll = FabricRoll::find($roll_id);
                $roll->offloaded_at = Carbon::now();
                $roll->offloaded_by = auth()->user()->id;
                $roll->save($data_update);
                $offloaded_roll[] = $roll;
            }

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

        $query = FabricRoll::where('packinglist_id', $packinglist_id)->get();

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
            ->editColumn('offloaded_at', function ($row) {
                if (!$row->offloaded_at) { return null; }
                $offloaded_at = Carbon::createFromFormat('Y-m-d H:i:s', $row->offloaded_at);
                $readable_offloaded_at = $offloaded_at->format('d F y, H:i');
                return $readable_offloaded_at;
            })
            ->toJson();
    }
}
