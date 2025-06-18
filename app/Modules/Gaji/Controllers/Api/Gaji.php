<?php

namespace App\Modules\Gaji\Controllers\Api;

use App\Controllers\BaseControllerApi;
use App\Modules\Gaji\Models\GajiModel;
use App\Modules\Log\Models\LogModel;

class Gaji extends BaseControllerApi
{
    protected $format       = 'json';
    protected $modelName    = GajiModel::class;
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

    public function create()
    {
        $rules = [
            'golongan_id' => [
                'rules'  => 'required',
                'errors' => []
            ],
            'gaji_golongan' => [
                'rules'  => 'required',
                'errors' => []
            ],
            'gaji_masa_kerja' => [
                'rules'  => 'required',
                'errors' => []
            ],
            'gaji_pokok' => [
                'rules'  => 'required',
                'errors' => []
            ],
        ];

        if ($this->request->getJSON()) {
            $json = $this->request->getJSON();
            $data = [
                'golongan_id' => $json->golongan_id,
                'gaji_golongan' => $json->gaji_golongan,
                'gaji_masa_kerja' => $json->gaji_masa_kerja,
                'gaji_pokok' => $json->gaji_pokok
            ];
        } else {
            $data = [
                'golongan_id' => $this->request->getPost('golongan_id'),
                'gaji_golongan' => $this->request->getPost('gaji_golongan'),
                'gaji_masa_kerja' => $this->request->getPost('gaji_masa_kerja'),
                'gaji_pokok' => $this->request->getPost('gaji_pokok')
            ];
        }

        if (!$this->validate($rules)) {
            $response = [
                'status' => false,
                'message' => lang('App.isRequired'),
                'data' => $this->validator->getErrors(),
            ];
            return $this->respond($response, 200);
        } else {
            $this->model->save($data);
            $lastId =  $this->model->getInsertID();

            //Save Log
            $this->log->save(['keterangan' => session('fullname') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Save Gaji ID: ' . $lastId, 'user_id' => session('id')]);

            $response = [
                'status' => true,
                'message' => lang('App.saveSuccess'),
                'data' => [],
            ];
            return $this->respond($response, 200);
        }
    }

    public function update($id = NULL)
    {
        $rules = [
            'golongan_id' => [
                'rules'  => 'required',
                'errors' => []
            ],
            'gaji_golongan' => [
                'rules'  => 'required',
                'errors' => []
            ],
            'gaji_masa_kerja' => [
                'rules'  => 'required',
                'errors' => []
            ],
            'gaji_pokok' => [
                'rules'  => 'required',
                'errors' => []
            ],
        ];

        if ($this->request->getJSON()) {
            $json = $this->request->getJSON();
            $data = [
                'golongan_id' => $json->golongan_id,
                'gaji_golongan' => $json->gaji_golongan,
                'gaji_masa_kerja' => $json->gaji_masa_kerja,
                'gaji_pokok' => $json->gaji_pokok
            ];
        } else {
            $input = $this->request->getRawInput();
            $data = [
                'golongan_id' => $input['golongan_id'],
                'gaji_golongan' => $input['gaji_golongan'],
                'gaji_masa_kerja' => $input['gaji_masa_kerja'],
                'gaji_pokok' => $input['gaji_pokok'],
            ];
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
            $this->log->save(['keterangan' => session('fullname') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Update Gaji ID: ' . $id, 'user_id' => session('id')]);

            $response = [
                'status' => true,
                'message' => lang('App.updSuccess'),
                'data' => [],
            ];
            return $this->respond($response, 200);
        }
    }

    public function delete($id = null)
    {
        $hapus = $this->model->find($id);

        if ($hapus) {
            $this->model->delete($id);

            //Save Log
            $this->log->save(['keterangan' => session('fullname') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Delete Gaji ID: ' . $id, 'user_id' => session('id')]);

            $response = [
                'status' => true,
                'message' => lang('App.delSuccess'),
                'data' => [],
            ];
            return $this->respond($response, 200);
        } else {
            $response = [
                'status' => false,
                'message' => lang('App.delFailed'),
                'data' => [],
            ];
            return $this->respond($response, 200);
        }
    }
}
