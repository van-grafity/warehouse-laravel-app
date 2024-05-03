<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UserRecords;


class Invoice extends Model
{
    use HasFactory, SoftDeletes, UserRecords;

    protected $fillable = [
        'invoice_number',
        'container_number',
        'incoming_date',
        'offloaded_date',
        'supplier_id',
        'flag_opened',
        'flag_offloaded',
        'received_at',
    ];

    protected static function booted(): void
    {
        static::deleted(function (Invoice $invoice) {
            $packinglists = $invoice->packinglists;
            foreach ($packinglists as $key => $packinglist) {
                $packinglist->delete();
            }
        });
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function packinglists()
    {
        return $this->hasMany(Packinglist::class,'invoice_id','id');
    }
}
