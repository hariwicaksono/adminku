<?php

namespace App\Modules\User\Controllers\Api;

use App\Controllers\BaseControllerApi;
use App\Modules\Group\Models\GroupUserModel;
use App\Modules\User\Models\UserModel;
use App\Modules\Log\Models\LogModel;
use App\Modules\Role\Models\RoleUserModel;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use ReflectionException;

class User extends BaseControllerApi
{
    protected $format       = 'json';
    protected $modelName    = UserModel::class;
    protected $log;
    protected $group;
    protected $role;

    public function __construct()
    {
        $this->log = new LogModel();
        $this->group = new GroupUserModel();
        $this->role = new RoleUserModel();
    }


    public function index()
    {
        $data = $this->model->findAll();
        foreach ($data as &$user) {
            $query = $this->role->getUserHasRoles($user['user_id']);
            $user['roles'] = $query;
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

    public function show($id = null)
    {
        $data = $this->model->where('email', $id)->first();
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
            'email' => [
                'rules'  => 'required',
                'errors' => []
            ],
            'fullname' => [
                'rules'  => 'required',
                'errors' => []
            ],
            'username' => [
                'rules'  => 'required',
                'errors' => []
            ],
            'password' => [
                'rules'  => 'required',
                'errors' => []
            ],
            'group_id' => [
                'rules'  => 'required',
                'errors' => []
            ],
        ];

        if ($this->request->getJSON()) {
            $json = $this->request->getJSON();
            $idGroup = $json->group_id;
            $data = [
                'email' => $json->email,
                'fullname' => $json->fullname,
                'username' => $json->username,
                'password' => $json->password,
                'user_type' => $json->group_id,
                'is_active' => 1
            ];
        } else {
            $idGroup = $this->request->getPost('group_id');
            $data = [
                'email' => $this->request->getPost('email'),
                'fullname' => $this->request->getPost('fullname'),
                'username' => $this->request->getPost('username'),
                'password' => $this->request->getPost('password'),
                'user_type' => $this->request->getPost('user_type'),
                'is_active' => 1
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
            $idUser =  $this->model->getInsertID();

            $dataGroup = [
                'user_id' => $idUser,
                'group_id' => $idGroup
            ];
            $this->group->save($dataGroup);

            //Save Log
            $this->log->save(['keterangan' => session('fullname') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Save User ID: ' . $idUser, 'user_id' => session('id')]);

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
            'email' => [
                'rules'  => 'required',
                'errors' => []
            ],
            'fullname' => [
                'rules'  => 'required',
                'errors' => []
            ],
        ];

        if ($this->request->getJSON()) {
            $json = $this->request->getJSON();
            $data = [
                'email' => $json->email,
                'fullname' => $json->fullname
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
            $this->log->save(['keterangan' => session('fullname') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Update User ID: ' . $id, 'user_id' => session('id')]);

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

        //Default role 1 jangan dihapus
        if ($id == '1' || $id == '2') :
            $response = ['status' => false, 'message' => lang('App.delFailed'), 'data' => []];
            return $this->respond($response, 200);
        endif;
        //

        if ($hapus) {
            $this->model->delete($id);

            //Save Log
            $this->log->save(['keterangan' => session('fullname') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Delete User ID: ' . $id, 'user_id' => session('id')]);

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

    public function setActive($id = NULL)
    {
        if ($this->request->getJSON()) {
            $json = $this->request->getJSON();
            $data = [
                'is_active' => $json->is_active
            ];
        } else {
            $input = $this->request->getRawInput();
            $data = [
                'is_active' => $input['is_active']
            ];
        }

        if ($data > 0) {
            $this->model->update($id, $data);

            //Save Log
            $this->log->save(['keterangan' => session('fullname') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Update User ID: ' . $id, 'user_id' => session('id')]);

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

    public function setRole($id = NULL)
    {
        if ($this->request->getJSON()) {
            $json = $this->request->getJSON();
            $data = [
                'user_type' => $json->user_type
            ];
        } else {
            $input = $this->request->getRawInput();
            $data = [
                'user_type' => $input['user_type']
            ];
        }

        if ($data > 0) {
            $this->model->update($id, $data);

            //Save Log
            $this->log->save(['keterangan' => session('fullname') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Update User ID: ' . $id, 'user_id' => session('id')]);

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

    public function changePassword()
    {
        $rules = [
            'email' => 'required',
            'password' => 'required|min_length[8]|max_length[255]',
            'verify' => 'required|matches[password]'
        ];

        $input = $this->getRequestInput();

        if (!$this->validate($rules)) {
            return $this->getResponse(
                [
                    'status' => false,
                    'message' => 'Error',
                    'data' => $this->validator->getErrors()
                ],
                ResponseInterface::HTTP_OK
            );
        }

        $user = $this->model->where(['email' => $input['email']])->first();
        $user_id = $user['user_id'];
        $user_data = [
            'password' => $input['password'],
        ];
        if ($this->model->update($user_id, $user_data)) {
            return $this->getResponse(
                [
                    'status' => true,
                    'message' => lang('App.passChanged'),
                    'data' => []
                ],
                ResponseInterface::HTTP_OK
            );
        } else {
            return $this->getResponse(
                [
                    'status' => false,
                    'message' => lang('App.regFailed'),
                    'data' => []
                ],
                ResponseInterface::HTTP_OK
            );
        }
    }

    public function setGroup($id = NULL)
    {
        $loginGroup = $this->group->where('user_id', $id)->first();
        $loginGroupId = $loginGroup['group_user_id'];
        //var_dump($loginGroup);die;

        if ($this->request->getJSON()) {
            $json = $this->request->getJSON();
            $data = [
                'user_id' => $id,
                'group_id' => $json->group_id
            ];
        } else {
            $input = $this->request->getRawInput();
            $data = [
                'user_id' => $id,
                'group_id' => $input['group_id']
            ];
        }

        if ($data > 0) {
            $this->group->update($loginGroupId, $data);

            //Save Log
            $this->log->save(['keterangan' => session('fullname') . '(' . session('email') . ') ' . strtolower(lang('App.do')) . ' Update User ID: ' . $id, 'user_id' => session('id')]);

            $response = [
                'status' => true,
                'message' => lang('App.updSuccess'),
                'data' => []
            ];
            return $this->respond($response, 200);
        } else {
            $response = [
                'status' => false,
                'message' => lang('App.delFailed'),
                'data' => []
            ];
            return $this->respond($response, 200);
        }
    }

    public function updateRoles($id = null)
    {
        $rules = [
            'roles'    => 'permit_empty|is_array',
            'roles.*'  => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'status' => false,
                'message' => 'Validasi gagal',
                'data' => $this->validator->getErrors(),
            ], 400);
        }

        $json = $this->request->getJSON();
        $roleIds = $json->roles ?? [];

        $db = \Config\Database::connect();

        // Cek user ada atau tidak
        $user = $db->table('users')->getWhere(['user_id' => $id])->getRow();
        if (!$user) {
            return $this->respond([
                'status' => false,
                'message' => 'User tidak ditemukan',
            ], 404);
        }

        // Validasi: Pastikan semua role_id memang ada di tabel roles
        if (!empty($roleIds)) {
            $existingRoles = $db->table('roles')
                ->select('role_id')
                ->whereIn('role_id', $roleIds)
                ->get()
                ->getResultArray();

            $validRoleIds = array_column($existingRoles, 'role_id');
        } else {
            $validRoleIds = [];
        }

        try {
            $builder = $db->table('role_user');

            // Hapus semua role lama
            $builder->where('user_id', $id)->delete();

            // Tambah yang baru (jika ada)
            if (!empty($validRoleIds)) {
                $insertData = [];
                foreach ($validRoleIds as $rid) {
                    $insertData[] = [
                        'user_id' => $id,
                        'role_id' => $rid
                    ];
                }
                $builder->insertBatch($insertData);
            }

            return $this->respond([
                'status' => true,
                'message' => 'Roles berhasil diperbarui',
            ], 200);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => false,
                'message' => 'Gagal memperbarui roles',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
