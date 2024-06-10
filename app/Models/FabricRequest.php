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
        'fbr_id',
        'fbr_serial_number',
        'fbr_status_print',
        'fbr_remark',
        'fbr_created_at',
        'fbr_updated_at',
        'laying_planning_id',
        'laying_planning_serial_number',
        'gl_number',
        'color',
        'style',
        'fabric_type',
        'fabric_po',
        'laying_planning_detail_id',
        'table_number',
        'qty_required',
        'last_sync_by',
        'last_sync_at',
        'flag_issued',
    ];

    public function isFabricRequestExist( $fbr_id = null )
    {
        if(!$fbr_id) { return false; }
        $fabric_request = $this->where('fbr_id', $fbr_id)->first();
        if(!$fabric_request) { return false; }

        return true;
    }

    public function check_serial_number( $fabric_request )
    {
        $is_serial_number_exist = $this->isSerialNumberExist($fabric_request->fbr_serial_number);
        if($is_serial_number_exist) {
            $fabric_request_from_db = $this->where('fbr_serial_number',$fabric_request->fbr_serial_number)->first();
            if($fabric_request_from_db->fbr_id != $fabric_request->fbr_id) {
                $this->delete($fabric_request_from_db->id);
            }
        }
    }

    public function isSerialNumberExist( $fbr_serial_number = null )
    {
        if(!$fbr_serial_number) { return false; }
        $fabric_request = $this->where('fbr_serial_number', $fbr_serial_number)->first();
        if(!$fabric_request) { return false; }

        return true;
    }

    public function fabric_rolls()
    {
        return $this->belongsToMany(Botol::class, 'fabric_issuances', 'fabric_request_id', 'fabric_roll_id');
    }
}
