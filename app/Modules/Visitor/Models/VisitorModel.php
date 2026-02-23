<?php

namespace App\Modules\Visitor\Models;

use CodeIgniter\Model;

class VisitorModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'visitors';
    protected $primaryKey           = 'id';
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
    protected $deletedField         = 'deleted_at';

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

    public function countByJenis($jenis)
    {
        $query = $this->where('device_type', $jenis)->countAllResults();
        return $query;
    }

    public function chartHarian($date)
    {
        $this->like('created_at', $date, 'after');
        return count($this->get()->getResultArray());
    }

    public function chartSatuTahun()
    {
        $data   = [];
        $labels = [];

        for ($i = 11; $i >= 0; $i--) {
            $bulan = date('Y-m', strtotime("-$i months"));
            $start = $bulan . '-01 00:00:00';
            $end   = date('Y-m-t 23:59:59', strtotime($start));

            $total = $this->where('created_at >=', $start)
                ->where('created_at <=', $end)
                ->countAllResults();
            $data[]   = $total;
            $labels[] = date('M Y', strtotime($start));
        }

        return [
            'cTahunan' => $data,
            'cLabels'  => $labels
        ];
    }

    public function chartJenis()
    {
        $this->select('device_type');
        $this->selectCount('id', 'jumlah');
        $this->groupBy('device_type');
        return $this->findAll();
    }
}
