<?php

namespace App\Modules\Gaji\Models;

use CodeIgniter\Model;

class GajiModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'gaji';
    protected $primaryKey           = 'gaji_id';
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

    public function getGajiById($id, $masakerja) 
	{
        $this->select("{$this->table}.*");
        $this->where("{$this->table}.golongan_id", $id);
        $this->where("{$this->table}.gaji_masa_kerja", $masakerja);
        return $this->first();
	}
}
