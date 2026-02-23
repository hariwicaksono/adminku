<?php

namespace App\Modules\Visitor\Controllers\Api;
/*
PT ITSHOP BISNIS DIGITAL
Website: https://itshop.biz.id
Toko Online: ITSHOP Purwokerto (https://Tokopedia.com/itshoppwt, https://Shopee.co.id/itshoppwt, https://Toco.id/store/itshop-purwokerto)
Dibuat oleh: Hari Wicaksono, S.Kom
02-2026
*/

use App\Controllers\BaseControllerApi;
use App\Modules\Visitor\Models\VisitorModel;
use App\Libraries\Settings;

class Visitor extends BaseControllerApi
{
    protected $format       = 'json';
    protected $modelName    = VisitorModel::class;
    protected $setting;

    public function __construct()
    {
        //memanggil Model
        $this->setting = new Settings();
    }

    public function index()
    {
        $jam = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '00'];
        foreach ($jam as $j) {
            $date = date('Y-m-d') . ' ' . $j;
            $cHarian[] = $this->model->chartHarian($date);
        }

        //Chart Jenis
        $chartJenis = $this->model->chartJenis();
        $cjJenis = [];
        $cjJumlah = [];
        $statusMap = [
            'Mobile' => 'Mobile',
            'Desktop' => 'Desktop',
            'Other' => 'Other'
        ];
        foreach ($chartJenis as $row) {
            $cjJenis[] = $statusMap[$row['device_type']] ?? 'Unknown';
            $cjJumlah[] = (int) $row['jumlah'];
        }
        $data['cjJenis'] = json_encode($cjJenis);
        $data['cjJumlah'] = json_encode($cjJumlah);

        $tahunan = $this->model->chartSatuTahun();

        $data = [
            'countMobile' =>  $this->model->countByJenis('Mobile'),
            'countDesktop' => $this->model->countByJenis('Desktop'),
            'countOther' => $this->model->countByJenis('Other'),
            'cHarian' => $cHarian,
            'cTahunan' => $tahunan['cTahunan'],
            'cLabels' => $tahunan['cLabels'],
            'cjJenis' => $cjJenis,
            'cjJumlah' => $cjJumlah,
        ];

        if (!empty($data)) {
            $response = [
                "status" => true,
                "message" => lang('App.getSuccess'),
                "data" => $data
            ];
            return $this->respond($response, 200);
        } else {
            $response = [
                'status' => false,
                'message' => lang('App.noData'),
                'data' => []
            ];
            return $this->respond($response, 200);
        }
    }
}
