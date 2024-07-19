<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rack extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'basic_number',
        'rack_type',
        'description',
    ];

    protected static function booted(): void
    {
        static::creating(function (Rack $rack) {
            $rack->serial_number = Static::generateRackSerialNumber($rack->rack_type, $rack->basic_number);
        });
    }

    public function location()
    {
        return $this->hasOneThrough(Location::class, RackLocation::class, 'rack_id', 'id', 'id', 'location_id')->whereNull('rack_locations.exit_at');
    }

    public static function generateRackSerialNumber($rack_type, $basic_number)
    {
        $normalize_number = normalizeNumber($basic_number, 2);
        
        if($rack_type == 'moveable') $code = 'MV';
        if($rack_type == 'fixed') $code = 'FX';
        if(!$rack_type) $code = 'UN';

        $serial_number = 'WHA-RCK-'. $code . '-' . $normalize_number;
        return $serial_number;
    }
}