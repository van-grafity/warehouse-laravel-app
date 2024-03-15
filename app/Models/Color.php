<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $fillable = [
        'color',
        'code',
    ];

    protected static function booted(): void
    {
        static::creating(function (Color $color) {
            $color->code = generateColorCode($color->color);
        });
    }
}
