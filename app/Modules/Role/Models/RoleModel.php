<?php

namespace App\Modules\Role\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'roles';
    protected $primaryKey           = 'role_id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = false;
    protected $allowedFields        = [];

    // Dates
    protected $useTimestamps        = true;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = '';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks       = true;
    protected $beforeInsert         = [];
    protected $afterInsert          = [];
    protected $beforeUpdate         = [];
    protected $afterUpdate          = [];
    protected $beforeFind           = [];
    protected $afterFind            = [];
    protected $beforeDelete         = [];
    protected $afterDelete          = [];

    public function getPermissions($roleId)
    {
        return $this->db->table('permission_role')
            ->select('permissions.*')
            ->join('permissions', 'permissions.id = permission_role.permission_id')
            ->where('permission_role.role_id', $roleId)
            ->get()->getResultArray();
    }
}
