<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FabricRequest;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Yajra\Datatables\Datatables;


class FabricRequestController extends Controller
{
    public function __construct()
    {
        Gate::define('manage', function ($user) {
            return $user->hasPermissionTo('fabric-request.manage');
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Fabric request',
            'page_title' => 'Fabric request',
            'can_manage' => auth()->user()->can('manage'),
        ];
        return view('pages.fabric-request.index', $data);
    }

    /**
     * Show Datatable Data.
     */
    public function dtable()
    {
        $query = FabricRequest::query();
        
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                $action_button = "
                    <a href='' class='btn btn-primary btn-sm' >Issue Fabric</a>
                ";
                return $action_button;
            })
            ->addColumn('serial_number', function($row){
                $serial_number = "<a href='". route('fabric-request.detail',$row->id)."' class='' data-toggle='tooltip' data-placement='top' title='Click for Detail'>$row->fbr_serial_number</a>";
                return $serial_number;
            })
            ->toJson();
    }

    /**
     * Syncronize data with Cutting App.
     */
    public function sync(Request $request)
    {
        
        try {
            $fabric_requests = $request->fabric_requests;
            $fbr_counter = 0;
            
            DB::transaction(function () use ($fabric_requests, &$fbr_counter) {
                $FabricRequestModel = new FabricRequest;
                foreach ($fabric_requests as $key_fbr => $fabric_request) {
                    $fabric_request = (object) $fabric_request;
                    $FabricRequestModel->check_serial_number($fabric_request);
                    $is_fabric_request_exist = $FabricRequestModel->isFabricRequestExist($fabric_request->fbr_id);
                    
                    $fabric_request_data = [
                        'fbr_serial_number' => $fabric_request->fbr_serial_number,
                        'fbr_status_print' => $fabric_request->fbr_status_print,
                        'fbr_remark' => $fabric_request->fbr_remark,
                        'fbr_created_at' => $fabric_request->fbr_created_at,
                        'fbr_updated_at' => $fabric_request->fbr_updated_at,
                        'gl_number' => $fabric_request->gl_number,
                        'color' => $fabric_request->color,
                        'style' => $fabric_request->style,
                        'fabric_type' => $fabric_request->fabric_type,
                        'fabric_po' => $fabric_request->fabric_po,
                        'laying_planning_id' => $fabric_request->laying_planning_id,
                        'laying_planning_serial_number' => $fabric_request->laying_planning_serial_number,
                        'laying_planning_detail_id' => $fabric_request->laying_planning_detail_id,
                        'table_number' => $fabric_request->table_number,
                        'qty_required' => $fabric_request->qty_required,
                        'last_sync_by' => auth()->user()->id,
                        'last_sync_at' => date('Y-m-d H:i:s'),
                    ];
                    
                    if(!$is_fabric_request_exist) {
                        $fabric_request_data['fbr_id'] = $fabric_request->fbr_id;
                        FabricRequest::firstOrCreate($fabric_request_data);
                    } else {
                        $fabric_request = FabricRequest::where('fbr_id', $fabric_request->fbr_id)->update($fabric_request_data);
                    }
    
                    $fbr_counter++;
                }

            });

            $data_return = [
                'status' => 'success',
                'message' => "Successfully Sync {$fbr_counter} Fabric Requests",
                'data' => [
                    'fabric_requests' => $fabric_requests,
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

    public function detail(string $id)
    {
        $fabric_request = FabricRequest::find($id);
        $data = [
            'title' => 'Fabric Request Detail',
            'page_title' => 'Fabric Request Detail',
            'fabric_request' => $fabric_request,
        ];
        return view('pages.fabric-request.detail', $data);
        
    }
    
}
