<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UserRecords;

class FabricIssue extends Model
{
    use HasFactory, UserRecords;

    protected $fillable = [
        'id_fbr',
        'id_roll',
    ];

    public function fabric_request()
    {
        return $this->belongsTo(FabricRequest::class, 'id_fbr', 'id');
    }
    public function fabric_roll()
    {
        return $this->belongsTo(FabricRoll::class, 'id_roll', 'id');
    }
}
