<?php

namespace App\Modules\User\Controllers\Api;

use App\Controllers\BaseControllerApi;
use App\Modules\Group\Models\GroupUserModel;
use App\Modules\User\Models\UserModel;
use App\Modules\Log\Models\LogModel;
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

    public function __construct()
    {
        $this->log = new LogModel();
        $this->group = new GroupUserModel();
    }


    public function index()
    {
        $data = $this->model->getUsers();
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
                'is_active' => 1
            ];
        } else {
            $idGroup = $this->request->getPost('group_id');
            $data = [
                'email' => $this->request->getPost('email'),
                'fullname' => $this->request->getPost('fullname'),
                'username' => $this->request->getPost('username'),
                'password' => $this->request->getPost('password'),
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
        if ($id == '1') :
            $response = ['status' => false, 'message' => lang('App.delFailed'), 'data' => []];
            return $this->respond($response, 200);
        endif;
        //

        if ($hapus) {
            $this->model->delete($id);
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
}
