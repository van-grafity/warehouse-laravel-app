<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RackLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'rack_id',
        'location_id',
        'entry_at',
        'exit_at',
    ];

    public function rack()
    {
        return $this->belongsTo(Rack::class, 'rack_id', 'id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public static function getRackByLocationId($location_id)
    {
        $rack_locations = self::join('racks','racks.id','rack_locations.rack_id')
            ->where('rack_locations.location_id', $location_id)
            ->whereNull('rack_locations.exit_at')
            ->select('racks.serial_number')
            ->get();

        $racks = $rack_locations->pluck('serial_number')->unique()->implode(' | ');
        return $racks;
    }

    public static function getTotalRackByLocationId($location_id)
    {
        $racks = self::join('racks','racks.id','rack_locations.rack_id')
            ->where('rack_locations.location_id', $location_id)
            ->whereNull('rack_locations.exit_at')
            ->select('racks.id')
            ->get();

        $total_roll = $racks->count();
        return $total_roll;
    }
}
