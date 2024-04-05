<?php

namespace App\Traits;

trait UserRecords
{
    public static function bootUserRecords()
    {
        // ## updating created_by and updated_by when model is created
        // ## Key Word of this feature => "Using Closures"
        static::creating(function ($model) {
            if (!$model->isDirty('created_by')) {
                $model->created_by = auth()->user()->id;
            }
            if (!$model->isDirty('updated_by')) {
                $model->updated_by = auth()->user()->id;
            }
        });

        // updating updated_by when model is updated
        static::updating(function ($model) {
            if (!$model->isDirty('updated_by')) {
                $model->updated_by = auth()->user()->id;
            }
        });
        
        // updating deleted_by when model is deleted
        static::deleting(function ($model) {
            $model->deleted_by = auth()->user()->id;
            $model->save(); // Save Changed for trigger updating event
        });
    }
}