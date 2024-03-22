<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UserRecords;


class Packinglist extends Model
{
    use HasFactory, SoftDeletes, UserRecords;

    protected $fillable = [
        'serial_number',
        'invoice_id',
        'buyer',
        'gl_number',
        'po_number',
        'color_id',
        'batch_number',
        'style',
        'fabric_content',
        'remark',
    ];


    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }
    
    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id', 'id');
    }
    
}
