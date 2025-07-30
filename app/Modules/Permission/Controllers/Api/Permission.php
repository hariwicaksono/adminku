<?php

namespace App\Modules\Permission\Controllers\Api;

use App\Controllers\BaseControllerApi;
use App\Modules\Permission\Models\PermissionModel;
use App\Modules\Log\Models\LogModel;

class Permission extends BaseControllerApi
{
    protected $format       = 'json';
    protected $modelName    = PermissionModel::class;
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
            'name' => [
                'rules'  => 'required',
                'errors' => []
            ],
        ];

        if ($this->request->getJSON()) {
            $json = $this->request->getJSON();
            $data = [
                'name' => $json->name,
            ];
        } else {
            $data = [
                'name' => $this->request->getPost('name'),
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
            $this->log->save(['keterangan' => session('fullname') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Save Permission ID: ' . $lastId, 'user_id' => session('id')]);

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
            'name' => [
                'rules'  => 'required',
                'errors' => []
            ],
        ];

        if ($this->request->getJSON()) {
            $json = $this->request->getJSON();
            $data = [
                'name' => $json->name,
            ];
        } else {
            $input = $this->request->getRawInput();
            $data = [
                'name' => $input['name'],
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
            $this->log->save(['keterangan' => session('fullname') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Update Permission ID: ' . $id, 'user_id' => session('id')]);

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
            $this->log->save(['keterangan' => session('fullname') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Delete Permission ID: ' . $id, 'user_id' => session('id')]);

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
