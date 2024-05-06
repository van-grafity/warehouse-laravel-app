<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RackLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'rack_id',
        'location_id',
        'entry_at',
        'exit_at',
    ];

    public function rack()
    {
        return $this->belongsTo(Rack::class, 'rack_id', 'id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }
}
