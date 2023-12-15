<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{

    protected $primaryKey = "id";
    public $incrementing = true;
    public $timestamps = false;
}
