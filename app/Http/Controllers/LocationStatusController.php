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
}
