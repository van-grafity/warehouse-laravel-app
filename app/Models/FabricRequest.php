<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UserRecords;

class FabricRequest extends Model
{
    use HasFactory, SoftDeletes, UserRecords;

    protected $fillable = [
        'api_fabric_request_id',
        'last_sync_by',
        'last_sync_at',
        'received_at',
        'issued_at',
        'remark',
        'relaxing_at',
        'relaxed_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function apiFabricRequest()
    {
        return $this->belongsTo(ApiFabricRequest::class, 'api_fabric_request_id');
    }

    public function existsByFbrId($fbr_id = null)
    {
        if (!$fbr_id) {
            return false;
        }

        return $this->whereHas('apiFabricRequest', function ($query) use ($fbr_id) {
            $query->where('fbr_id', $fbr_id);
        })->exists();
    }

    public function allocatedFabricRolls()
    {
        return $this->belongsToMany(FabricRoll::class, 'fabric_issuances', 'fabric_request_id', 'fabric_roll_id');
    }
}
