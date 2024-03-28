<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{
    use HasFactory, SoftDeletes;

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

    public function getOrCreateDataByName(Array $data_to_insert)
    {
        $builder = $this;
        $color = $data_to_insert['color'];
        $get_color = $builder->where('color', $color)->first();
        if(!$get_color){
            $colour_id = $builder->create([
                'color'=> $color,
            ]);
        } else {
            $colour_id = $get_color->id;
        }
        return $colour_id;
    }
}
