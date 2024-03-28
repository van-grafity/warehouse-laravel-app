<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UserRecords;


class FabricRollRack extends Model
{
    use HasFactory, SoftDeletes, UserRecords;

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
}
