<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rack;
use App\Models\Location;
use App\Models\RackLocation;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Carbon;

use Yajra\Datatables\Datatables;

class RackHistoryController extends Controller
{
     public function index()
    {
        $data = [
            'title' => 'Rack History',
            'page_title' => 'Rack History',
        ];
        return view('pages.rack-history.index', $data);
    }

     /**
     * Show Datatable Data.
     */
    public function dtable()
    {
       $query = Rack::leftJoin('rack_locations','rack_locations.rack_id','=','racks.id')
            ->leftJoin('locations','locations.id','=','rack_locations.location_id')
            ->whereNull('rack_locations.exit_at')
            ->select(
                'racks.id',
                'racks.serial_number',
                'locations.location as location',
                'rack_locations.entry_at'
            );
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                $action_button = "";
                if ($row->location != null){
                    $action_button .= "
                    <a href='". route('rack-history.detail',$row->id)."' class='btn btn-primary btn-sm'> Detail </a>
                ";
                }
                return $action_button;
            })

            ->editColumn('location', function($row) {
                return $row->location ?? '-';
            })

            ->editColumn('entry_at', function($row) {
                return $row->entry_at ? Carbon::parse($row->entry_at)->format('d F Y H:i:s') : '-';
            })

            ->toJson();
    }

    /**
    * Display a detail of this resource.
    */
    public function detail(string $id)
    {
        $rack = Rack::find($id);
        $data = [
            'title' => 'Rack History Detail',
            'page_title' => 'Rack History Detail',
            'rack' => $rack,
        ];
        return view('pages.rack-history.detail', $data);
    }

    public function dtable_roll_list()
    {
        $rack_id = request()->rack_id;
        $query = RackLocation::leftJoin('racks','rack_locations.rack_id','=','racks.id')
            ->leftJoin('locations','locations.id','=','rack_locations.location_id')
            ->where('rack_locations.rack_id', $rack_id)
            ->orderBy('entry_at','DESC')
            ->select(
                'racks.id',
                'racks.serial_number',
                'locations.location as location',
                'rack_locations.entry_at',
                'rack_locations.exit_at'
        );

        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('entry_at', function($row){
                return Carbon::parse($row->entry_at)->format('d F Y H:i:s');
            })
            ->addColumn('exit_at', function($row){
                return $row->exit_at ? Carbon::parse($row->exit_at)->format('d F Y H:i:s') : '-';
            })
            ->editColumn('location', function($row) {
                return $row->location ?? '-';
            })
            ->toJson();
    }
}
