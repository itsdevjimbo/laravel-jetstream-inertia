<?php
namespace App\Models;

use PDO;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole {

    protected $appends = [
        'permission_names'
    ];

    public function getPermissionNamesAttribute(){
        return $this->getPermissionNames();
    }
}
