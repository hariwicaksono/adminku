<?php

namespace App\Modules\Setting\Controllers\Api;

use App\Controllers\BaseControllerApi;
use App\Modules\Setting\Models\SettingModel;
use App\Modules\Log\Models\LogModel;

class Setting extends BaseControllerApi
{
    protected $format       = 'json';
    protected $modelName    = SettingModel::class;
    protected $log;

    public function __construct()
	{
		//memanggil Model
        $this->log = new LogModel();
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

    public function update($id = NULL)
    {
        $rules = [
            'setting_value' => [
                'rules'  => 'required',
                'errors' => []
            ],
        ];

        if ($this->request->getJSON()) {
            $json = $this->request->getJSON();
            $data = [
                'setting_value' => $json->setting_value,
            ];
        } else {
            $data = $this->request->getRawInput();
        }

        if (!$this->validate($rules)) {
            $response = [
                'status' => false,
                'message' => lang('App.updFailed'),
                'data' => $this->validator->getErrors(),
            ];
            return $this->respond($response, 200);
        } else {
            $this->model->update($id, $data);

            //Save Log
            $this->log->save(['keterangan' => session('fullname') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Update Setting ID: ' . $id, 'user_id' => session('id')]);

            $response = [
                'status' => true,
                'message' => lang('App.updSuccess'),
                'data' => [],
            ];
            return $this->respond($response, 200);
        }
    }

    public function upload()
    {
        //Maksimal ukuran 51200 KB = 50 MB
        $rules = [
            'image' => [
                'rules'  => 'uploaded[image]|max_size[image,51200]|is_image[image]',
                'errors' => []
            ],
        ];
        $id = $this->request->getVar('id');
        $image = $this->request->getFile('image');
        $fileName = $image->getRandomName();
        if (! $this->validateData([], $rules)) {
            $response = [
                'status' => false,
                'message' => lang('App.imgFailed'),
                'data' => $this->validator->getErrors(),
            ];
            return $this->respond($response, 200);
        } else {
            $path = "assets/images/";
            $moved = $image->move($path, $fileName);
            if ($moved) {
                $save = $this->model->update($id, [
                    'setting_value' => $path . $fileName
                ]);
                if ($save) {
                    return $this->respond(["status" => true, "message" => lang('App.imgSuccess'), "data" => [$path . $fileName]], 200);
                } else {
                    return $this->respond(["status" => false, "message" => lang('App.imgFailed'), "data" => []], 200);
                }
            }
        } 
    }

    public function setChange($id = NULL)
    {
        if ($this->request->getJSON()) {
            $json = $this->request->getJSON();
            $data = [
                'setting_value' => $json->setting_value,
            ];
        } else {
            $input = $this->request->getRawInput();
            $data = [
                'setting_value' => $input['setting_value']
            ];
        }

        if ($data > 0) {
            $this->model->update($id, $data);

            //Save Log
            $this->log->save(['keterangan' => session('fullname') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Update Setting ID: ' . $id, 'user_id' => session('id')]);
            
            $response = [
                'status' => true,
                'message' => lang('App.updSuccess'),
                'data' => []
            ];
            return $this->respond($response, 200);
        } else {
            $response = [
                'status' => false,
                'message' => lang('App.updFailed'),
                'data' => []
            ];
            return $this->respond($response, 200);
        }
    }
}
