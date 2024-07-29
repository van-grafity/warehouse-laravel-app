<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UserRecords;


class FabricRollRack extends Model
{
    use HasFactory, UserRecords;

    protected $fillable = [
        'fabric_roll_id',
        'rack_id',
        'stock_in_at',
        'stock_in_by',
        'stock_out_at',
        'stock_out_by',
    ];


    public function fabric_roll()
    {
        return $this->belongsTo(FabricRoll::class, 'fabric_roll_id', 'id');
    }
    public function rack()
    {
        return $this->belongsTo(Rack::class, 'rack_id', 'id');
    }

    public static function getGlNumberByRackId($rack_id)
    {
        $packinglists = FabricRoll::leftJoin('fabric_issuances', 'fabric_rolls.id', '=', 'fabric_issuances.fabric_roll_id')
            ->join('fabric_roll_racks', 'fabric_roll_racks.fabric_roll_id', '=', 'fabric_rolls.id')
            ->join('packinglists','packinglists.id','fabric_rolls.packinglist_id')
            ->join('rack_locations', 'rack_locations.rack_id', '=', 'fabric_roll_racks.rack_id')
            ->where('fabric_roll_racks.rack_id', $rack_id)
            ->whereNotNull('fabric_rolls.racked_at')
            ->whereNull('fabric_issuances.fabric_roll_id')
            ->whereNull('rack_locations.exit_at')
            ->groupBy('packinglists.id')
            ->select('packinglists.gl_number')
            ->get();

        $gl_numbers = $packinglists->pluck('gl_number')->unique()->implode(' | ');
        return $gl_numbers;
    }

    public static function getColorByRackId($rack_id)
    {
        $colors = FabricRoll::leftJoin('fabric_issuances', 'fabric_rolls.id', '=', 'fabric_issuances.fabric_roll_id')
            ->join('fabric_roll_racks', 'fabric_roll_racks.fabric_roll_id', '=', 'fabric_rolls.id')
            ->join('packinglists','packinglists.id','fabric_rolls.packinglist_id')
            ->join('colors','colors.id','packinglists.color_id')
            ->join('rack_locations', 'rack_locations.rack_id', '=', 'fabric_roll_racks.rack_id')
            ->where('fabric_roll_racks.rack_id', $rack_id)
            ->whereNotNull('fabric_rolls.racked_at')
            ->whereNull('fabric_issuances.fabric_roll_id')
            ->whereNull('rack_locations.exit_at')
            ->groupBy('packinglists.id')
            ->select('colors.color')
            ->get();

        $color_list = $colors->pluck('color')->unique()->implode(' | ');
        return $color_list;
    }

    public static function getBatchByRackId($rack_id)
    {
        $packinglists = FabricRoll::leftJoin('fabric_issuances', 'fabric_rolls.id', '=', 'fabric_issuances.fabric_roll_id')
            ->join('fabric_roll_racks', 'fabric_roll_racks.fabric_roll_id', '=', 'fabric_rolls.id')
            ->join('packinglists','packinglists.id','fabric_rolls.packinglist_id')
            ->join('rack_locations', 'rack_locations.rack_id', '=', 'fabric_roll_racks.rack_id')
            ->where('fabric_roll_racks.rack_id', $rack_id)
            ->whereNotNull('fabric_rolls.racked_at')
            ->whereNull('fabric_issuances.fabric_roll_id')
            ->whereNull('rack_locations.exit_at')
            ->groupBy('packinglists.id')
            ->select('packinglists.batch_number')
            ->get();

        $batch = $packinglists->pluck('batch_number')->unique()->implode(' | ');
        return $batch;
    }

    public static function getTotalRollByRackId($rack_id)
    {
        $fabric_rolls = FabricRoll::leftJoin('fabric_issuances', 'fabric_rolls.id', '=', 'fabric_issuances.fabric_roll_id')
            ->join('fabric_roll_racks', 'fabric_roll_racks.fabric_roll_id', '=', 'fabric_rolls.id')
            ->join('rack_locations', 'rack_locations.rack_id', '=', 'fabric_roll_racks.rack_id')
            ->where('fabric_roll_racks.rack_id', $rack_id)
            ->whereNotNull('fabric_rolls.racked_at')
            ->whereNull('fabric_issuances.fabric_roll_id')
            ->whereNull('rack_locations.exit_at')
            ->select('fabric_rolls.id')
            ->get();

        $total_roll = $fabric_rolls->count();
        return $total_roll;
    }

    public static function getColorByLocationId($location_id)
    {
        $colors = FabricRoll::leftJoin('fabric_issuances', 'fabric_rolls.id', '=', 'fabric_issuances.fabric_roll_id')
            ->join('fabric_roll_racks', 'fabric_roll_racks.fabric_roll_id', '=', 'fabric_rolls.id')
            ->join('packinglists','packinglists.id','fabric_rolls.packinglist_id')
            ->join('colors','colors.id','packinglists.color_id')
            ->join('rack_locations', 'rack_locations.rack_id', '=', 'fabric_roll_racks.rack_id')
            ->where('rack_locations.location_id', $location_id)
            ->whereNotNull('fabric_rolls.racked_at')
            ->whereNull('fabric_issuances.fabric_roll_id')
            ->whereNull('rack_locations.exit_at')
            ->groupBy('packinglists.id')
            ->select('colors.color')
            ->get();

        $color_list = $colors->pluck('color')->unique()->implode(' | ');
        return $color_list;
    }

    public static function getGlNumberByLocationId($location_id)
    {
        $packinglists = FabricRoll::leftJoin('fabric_issuances', 'fabric_rolls.id', '=', 'fabric_issuances.fabric_roll_id')
            ->join('fabric_roll_racks', 'fabric_roll_racks.fabric_roll_id', '=', 'fabric_rolls.id')
            ->join('packinglists','packinglists.id','fabric_rolls.packinglist_id')
            ->join('rack_locations', 'rack_locations.rack_id', '=', 'fabric_roll_racks.rack_id')
            ->where('rack_locations.location_id', $location_id)
            ->whereNotNull('fabric_rolls.racked_at')
            ->whereNull('fabric_issuances.fabric_roll_id')
            ->whereNull('rack_locations.exit_at')
            ->groupBy('packinglists.id')
            ->select('packinglists.gl_number')
            ->get();
            
        $gl_numbers = $packinglists->pluck('gl_number')->unique()->implode(' | ');
        return $gl_numbers;
    }
}
