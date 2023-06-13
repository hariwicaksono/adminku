<?php

namespace App\Modules\Group\Models;

use CodeIgniter\Model;

class GroupUserModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'groups_user';
    protected $primaryKey           = 'id_group_user';
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

    public function getGroupById($user_id) 
	{
        $this->select("{$this->table}.*, groups.nama_group, users.email, users.fullname, users.username");
        $this->join("groups", "groups.id_group = {$this->table}.id_group", "left");
        $this->join("users", "users.id_user = {$this->table}.id_user");
        $this->where("{$this->table}.id_user", $user_id);
        return $this->get()->getRowArray();
	}
}
