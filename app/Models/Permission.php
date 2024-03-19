<?php

namespace App\Models;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{

    public function permission_category()
    {
        return $this->belongsTo(PermissionCategory::class, 'permission_category_id', 'id');
    }
}
