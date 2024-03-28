<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Packinglist;
use App\Models\FabricRoll;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;


class FabricRollController extends Controller
{
    public function __construct()
    {
        Gate::define('manage', function ($user) {
            return $user->hasPermissionTo('packinglist.manage');
        });
    }

    /**
     * Show Datatable Data.
     */
    public function dtable()
    {
        $query = FabricRoll::query()->where('packinglist_id', request()->packinglist_id);
        
        return DataTables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                return '
                    <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="show_modal_edit(\'modal_fabric_roll\', '.$row->id.')">Edit</a>
                    <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="show_modal_delete('.$row->id.')">Delete</a>
                ';
            })
            ->addColumn('checkbox', function ($row) {
                if($row->roll_rack_id) { return null; }
                
                $checkbox_element = '
                    <div class="form-group mb-0">
                        <div class="custom-control custom-checkbox">
                            <input 
                                id="roll_checkbox_'. $row->id .'" 
                                name="selected_roll[]" 
                                class="custom-control-input checkbox-roll-control" 
                                type="checkbox" 
                                value="'. $row->id .'"
                                onchange="checkbox_clicked()" 
                            >
                            <label for="roll_checkbox_'. $row->id .'" class="custom-control-label"></label>
                        </div>
                    </div>
                ';
                return $checkbox_element;
            })
            ->toJson();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // ## Unique Roll Number each Packing List
            $is_roll_number_exist = FabricRoll::is_roll_number_exist($request->packinglist_id, $request->roll_number);
            
            if($is_roll_number_exist) {
                $data_return = [
                    'status' => 'error',
                    'message' => 'Roll Number have been exist on this Packing List ',
                ];
                return response()->json($data_return);
            }

            $packinglist = Packinglist::find($request->packinglist_id);

            $fabric_roll = FabricRoll::firstOrCreate([
                'packinglist_id' => $packinglist->id,
                'serial_number' => FabricRoll::generate_serial_number($packinglist->color->code, $packinglist->batch_number, $request->roll_number),
                'roll_number' => $request->roll_number,
                'kgs' => $request->kgs,
                'lbs' => $request->lbs,
                'yds' => $request->yds,
            ]);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully added new Fabric Roll (' . $fabric_roll->serial_number . ')',
                'data' => [
                    'fabric_roll' => $fabric_roll,
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
            $fabric_roll = FabricRoll::find($id);
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully get Fabric Roll (' . $fabric_roll->serial_number . ')',
                'data' => [
                    'fabric_roll' => $fabric_roll,
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
            $fabric_roll = FabricRoll::find($id);
            $fabric_roll->kgs = $request->kgs;
            $fabric_roll->lbs = $request->lbs;
            $fabric_roll->yds = $request->yds;
            $fabric_roll->save();
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully updated Fabric Roll ('. $fabric_roll->serial_number .')',
                'data' => $fabric_roll
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
            $fabric_roll = FabricRoll::find($id);
            $fabric_roll->delete();
            $data_return = [
                'status' => 'success',
                'data'=> $fabric_roll,
                'message'=> 'Fabric Roll '.$fabric_roll->serial_number.' successfully Deleted!',
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

    public function mass_delete(Request $request)
    {
        try {
            $selected_roll_list = $request->selected_roll_id;
            $deleted_roll = [];

            DB::transaction(function () use ($selected_roll_list, &$deleted_roll) {
                foreach ($selected_roll_list as $key => $roll_id) {
                    $fabric_roll = FabricRoll::find($roll_id);
                    if(!$fabric_roll) { throw new \Exception("Data Roll Not Found", 1); }
                    $deleted_roll[] = $fabric_roll;
                    $fabric_roll->delete();
                }
            });
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully removed ' . count($deleted_roll) . ' selected Roll',
                'data' => [
                    'deleted_roll' => $deleted_roll,
                ]
            ];
            return response()->json($data_return);
            
        } catch (\Throwable $th) {
            $data_return = [
                'status' => 'error',
                'message' => $th->getMessage(),
            ];
            return response()->json($data_return);
        }
    }
}
