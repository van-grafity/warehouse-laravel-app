<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UserRecords;


class FabricRoll extends Model
{
    use HasFactory, SoftDeletes, UserRecords;

    protected $fillable = [
        'serial_number',
        'packinglist_id',
        'roll_number',
        'kgs',
        'lbs',
        'yds',
        'width',
        'racked_at',
        'racked_by',
    ];


    public function packinglist()
    {
        return $this->belongsTo(Packinglist::class, 'packinglist_id', 'id');
    }

    public function rack()
    {
        return $this->hasOneThrough(Rack::class, FabricRollRack::class, 'fabric_roll_id', 'id', 'id', 'rack_id');
    }

    public static function is_roll_number_exist($packinglist_id, $roll_number)
    {
        $fabric_roll = static::where('packinglist_id', $packinglist_id)->where('roll_number', $roll_number)->first();
        if($fabric_roll) {
            return true;
        } else {
            return false;
        }
    }

    public static function generate_serial_number($color_code, $batch_number, $roll_number)
    {
        $serial_number = 'WHA-ROLL-' . generateRandomString(). '-' .$color_code. '-' . $batch_number .'-' .normalizeNumber($roll_number);
        return $serial_number;
    }
}
