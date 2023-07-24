<?php

namespace App\Modules\Log\Controllers\Api;

use App\Controllers\BaseControllerApi;
use App\Modules\Log\Models\LogModel;
use App\Libraries\Settings;

class Log extends BaseControllerApi
{
    protected $format       = 'json';
    protected $modelName    = LogModel::class;
    protected $setting;

    public function __construct()
	{
		//memanggil Model
		$this->setting = new Settings();
	}

    public function index()
    {
        $data = $this->model->findAll();
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

    public function show($id = null)
    {
        $input = $this->request->getVar();
        $start = $input['tgl_start'];
        $end = $input['tgl_end'];
        $data = $this->model->where('user_id', $id)->where("DATE(created_at) BETWEEN '$start' AND '$end'", null, false)->orderBy('created_at', 'DESC')->findAll();
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
