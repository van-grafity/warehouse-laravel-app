<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ApiFabricRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'fbr_id',
        'fbr_serial_number',
        'fbr_status_print',
        'fbr_remark',
        'fbr_requested_at',
        'fbr_requested_by',
        'fbr_created_at',
        'fbr_updated_at',
        'fbr_laying_planning_id',
        'fbr_laying_planning_serial_number',
        'fbr_style',
        'fbr_fabric_type',
        'fbr_fabric_po',
        'fbr_laying_planning_detail_id',
        'fbr_gl_number',
        'fbr_color',
        'fbr_table_number',
        'fbr_qty_required'
    ];

    public function fabricRequest()
    {
        return $this->hasOne(FabricRequest::class, 'api_fabric_request_id');
    }
}
