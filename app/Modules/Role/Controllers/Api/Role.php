<?php

namespace App\Modules\Role\Controllers\Api;

use App\Controllers\BaseControllerApi;
use App\Modules\Role\Models\RoleModel;
use App\Modules\Permission\Models\PermissionRoleModel;
use App\Modules\Log\Models\LogModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Role extends BaseControllerApi
{
    protected $format       = 'json';
    protected $modelName    = RoleModel::class;
    protected $log;
    protected $permission;

    public function __construct()
    {
        //memanggil Model
        $this->log = new LogModel();
        $this->permission = new PermissionRoleModel();
    }

    public function index()
    {
        $data = $this->model->findAll();
        foreach ($data as &$role) {
            $query = $this->permission->getRolesHasPermissions($role['role_id']);
            $role['permissions'] = $query;
        }
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
            $this->log->save(['keterangan' => session('fullname') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Save Role ID: ' . $lastId, 'user_id' => session('id')]);

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
            $this->log->save(['keterangan' => session('fullname') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Update Role ID: ' . $id, 'user_id' => session('id')]);

            $response = [
                'status' => true,
                'message' => lang('App.updSuccess'),
                'data' => ['url' => base_url('/logout')],
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
            $this->log->save(['keterangan' => session('fullname') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Delete Role ID: ' . $id, 'user_id' => session('id')]);

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

    public function getPermissions($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('permission_role');
        $permissions = $builder->select('permission_id')
            ->where('role_id', $id)
            ->get()
            ->getResultArray();

        $permissionIds = array_column($permissions, 'permission_id');

        return $this->response->setJSON([
            'permission_ids' => $permissionIds,
        ]);
    }

    public function updatePermissions($id = null)
    {
        $rules = [
            'permission_ids' => 'permit_empty|is_array',
            'permission_ids.*' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'status' => false,
                'message' => lang('App.updFailed'),
                'data' => $this->validator->getErrors(),
            ], 200);
        }

        $json = $this->request->getJSON();
        $permissionIds = $json->permission_ids ?? [];

        $db = \Config\Database::connect();
        $builder = $db->table('permission_role');

        try {
            $builder->where('role_id', $id)->delete();

            if (!empty($permissionIds)) {
                $insertData = [];
                foreach ($permissionIds as $pid) {
                    $insertData[] = [
                        'role_id' => $id,
                        'permission_id' => $pid
                    ];
                }
                $builder->insertBatch($insertData);
            }

            // Simpan log (jika ada)
            $this->log->save([
                'keterangan' => session('fullname') . '(' . session('email') . ') Update Permissions Role ID: ' . $id,
                'user_id' => session('id')
            ]);

            return $this->respond([
                'status' => true,
                'message' => lang('App.updSuccess'),
                'data' => [],
            ], 200);
        } catch (DatabaseException $e) {
            return $this->respond([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ], 500);
        }
    }
}
