<?php

namespace App\Modules\Role\Models;

use CodeIgniter\Model;

class RoleUserModel extends Model
{
    protected $table = 'role_user';
    protected $primaryKey = ['role_id', 'user_id'];
    protected $allowedFields = ['role_id', 'user_id'];
    public $timestamps = false;

    public function getUserHasRoles($user_id)
    {
        $this->select("roles.role_id, roles.name");
        $this->join("roles", "roles.role_id = {$this->table}.role_id");
        $this->where("{$this->table}.user_id", $user_id);
        return $this->findAll();
    }
}
