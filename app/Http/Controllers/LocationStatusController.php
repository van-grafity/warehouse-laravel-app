<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rack;
use App\Models\Location;
use App\Models\RackLocation;
use App\Models\FabricRollRack;

use Yajra\Datatables\Datatables;

class LocationStatusController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Location Status',
            'page_title' => 'Location Status List',
        ];
        return view('pages.location-status.index', $data);
    }

     /**
     * Show Datatable Data.
     */
    public function dtable()
    {
        $query = Location::leftJoin('rack_locations','rack_locations.location_id','=','locations.id')
            ->leftJoin('racks','racks.id','=','rack_locations.rack_id')
            ->select(
                'locations.id',
                'locations.location',
            )
            ->distinct();

        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                $action_button = "
                    <a href='". route('location-status.detail',$row->id)."' class='btn btn-primary btn-sm' >Detail</a>
                ";
                return $action_button;
            })

            ->addColumn('rack', function($row) {
                $racks = Racklocation::getRackByLocationId($row->id);
                return $racks ? $racks : '-';
            })
            ->addColumn('color', function($row) {
                $colors = FabricRollRack::getColorByLocationId($row->id);
                return $colors ? $colors : '-';
            })
            ->addColumn('gl_number', function($row) {
                $gl_numbers = FabricRollRack::getGlNumberByLocationId($row->id);
                return $gl_numbers ? $gl_numbers : '-';
            })
            ->addColumn('total_rack', function($row) {
                $total_rack = Racklocation::getTotalRackByLocationId($row->id);
                return $total_rack;
            })
            ->toJson();
    }

    /**
    * Display a detail of this resource.
    */
    public function detail(string $id)
    {
        $location = Location::find($id);
        $data = [
            'title' => 'Location Detail',
            'page_title' => 'Location Detail',
            'location' => $location,
        ];
        return view('pages.location-status.detail', $data);
    }

    public function dtable_roll_list()
    {
        $location_id = request()->location_id;
        $query = Rack::leftJoin('rack_locations','rack_locations.rack_id','=','racks.id')
            ->leftJoin('locations','locations.id','=','rack_locations.location_id')
            ->where('rack_locations.location_id', $location_id)
            ->whereNull('rack_locations.exit_at')
            ->select(
                'racks.id',
                'racks.serial_number',
        );

        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                $action_button = "
                    <a href='". route('rack-location.detail',$row->id)."' class='btn btn-primary btn-sm' >Detail</a>
                ";
                return $action_button;
            })
            ->addColumn('total_roll', function($row) {
                $total_roll = FabricRollRack::getTotalRollByRackId($row->id);
                return $total_roll;
            })
            ->toJson();
    }
}
