<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UserRecords;

class FabricIssuance extends Model
{
    use HasFactory, UserRecords;

    protected $fillable = [
        'fabric_request_id',
        'fabric_roll_id',
    ];

    public function fabric_request()
    {
        return $this->belongsTo(FabricRequest::class, 'fabric_request_id', 'id');
    }
    public function fabric_roll()
    {
        return $this->belongsTo(FabricRoll::class, 'fabric_roll_id', 'id');
    }
}
