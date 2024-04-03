<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'location',
        'description',
        'location_row_id',
    ];

    
    public function location_row()
    {
        return $this->belongsTo(LocationRow::class, 'location_row_id', 'id');
    }
}
