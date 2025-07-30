<?php

namespace App\Modules\Permission\Models;

use CodeIgniter\Model;

class PermissionRoleModel extends Model
{
    protected $table = 'permission_role';
    protected $primaryKey = ['permission_id', 'role_id'];
    protected $allowedFields = ['permission_id', 'role_id'];
    public $timestamps = false;

    public function getRolesHasPermissions($role_id)
    {
        $this->select("permissions.permission_id, permissions.name");
        $this->join("permissions", "permissions.permission_id = {$this->table}.permission_id");
        $this->where("{$this->table}.role_id", $role_id);
        return $this->findAll();
    }
}
