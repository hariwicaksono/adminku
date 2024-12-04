<?php

namespace App\Modules\Auth\Controllers\Api;

use App\Controllers\BaseControllerApi;
use App\Modules\Auth\Models\UserModel;
use App\Modules\Group\Models\GroupUserModel;
use App\Modules\Log\Models\LogModel;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use ReflectionException;

class Auth extends BaseControllerApi
{
    protected $format       = 'json';
    protected $modelName    = UserModel::class;
    protected $log;
    protected $group;

    public function __construct()
    {
        helper('app');
        $this->log = new LogModel();
        $this->group = new GroupUserModel();
    }

    /**
     * Register a new user
     * @return Response
     * @throws ReflectionException
     */
    public function register()
    {
        $rules = [
            'username' => 'required',
            'email' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]|max_length[255]'
        ];

        $input = $this->getRequestInput();

        if (!$this->validate($rules)) {
            return $this->getResponse(
                [
                    'status' => false,
                    'message' => $this->validator->getErrors()
                ],
                ResponseInterface::HTTP_OK
            );
        }

        $token = base64_encode(mt_rand(100000, 999999));
        $data = [
            'email' => $input['email'],
            'username' => $input['username'],
            'password' => $input['password'],
            'fullname' => $input['username'],
            'user_type' => 2,
            'is_active' => 0,
            'token' => $token
        ];
        $save = $this->model->save($data);
        $idUser = $this->model->getInsertID();

        $dataGroup = [
            'user_id' => $idUser,
            'group_id' => 2
        ];
        $this->group->save($dataGroup);

        if ($save) {
            helper('email');
            sendEmail("Verifikasi Akun", $input['email'], view('App\Modules\Auth\Views\email/verify', $data));
            return $this->getResponse(
                [
                    'status' => true,
                    'message' => lang('App.regSuccess'),
                    'data' => ['url' => base_url("")]
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

    /**
     * Authenticate Existing User
     * @return Response
     */
    public function login()
    {
        $rules = [
            'email' => 'required|min_length[6]|max_length[50]|valid_email|validateUser[email,password]',
            'password' => 'required|min_length[8]|max_length[255]|validateUser[email, password]'
        ];

        $errors = [
            'email' => ['validateUser' => lang('App.errorLogin')],
            'password' => ['validateUser' => lang('App.errorPassword')]
        ];

        $input = $this->getRequestInput();

        if (!$this->validate($rules, $errors)) {
            return $this->getResponse(
                [
                    'status' => false,
                    'message' => lang('App.invalid'),
                    'data' => $this->validator->getErrors()
                ],
                ResponseInterface::HTTP_OK
            );
        }

        return $this->getJWTForUser($input['email'], $input['remember']);
    }

    /**
     * Request Reset Password for user
     * @return Response
     * @throws ReflectionException
     */
    public function resetPassword()
    {
        $rules = [
            'email' => 'required|min_length[6]|max_length[50]|valid_email|is_not_unique[users.email]',
        ];

        $input = $this->getRequestInput();

        if (!$this->validate($rules)) {
            return $this->getResponse(
                [
                    'status' => false,
                    'message' => $this->validator->getErrors()
                ],
                ResponseInterface::HTTP_OK
            );
        }

        $token = base64_encode(mt_rand(100000, 999999));
        $data = [
            'email' => $input['email'],
            'token' => $token,
        ];

        $user = $this->model->where(['email' => $input['email']])->first();
        $user_id = $user['user_id'];
        $user_data = [
            'token' => $token,
        ];

        if ($this->model->update($user_id, $user_data)) {
            helper('email');
            sendEmail("Permintaan Reset Password", $input['email'], view('App\Modules\Auth\Views\email/reset', $data));
            return $this->getResponse(
                [
                    'status' => true,
                    'message' => lang('App.checkEmail'),
                    'data' => ['url' => base_url("")]
                ],
                ResponseInterface::HTTP_OK
            );
        } else {
            return $this->getResponse(
                [
                    'status' => false,
                    'message' => lang('App.reqFailed'),
                    'data' => []
                ],
                ResponseInterface::HTTP_OK
            );
        }
    }

    /**
     * Request Change password for user
     * @return Response
     * @throws ReflectionException
     */
    public function changePassword()
    {
        $rules = [
            'email' => 'required',
            'token' => 'required',
            'password' => 'required|min_length[8]|max_length[255]',
            'verify' => 'required|matches[password]'
        ];

        $input = $this->getRequestInput();

        if (!$this->validate($rules)) {
            return $this->getResponse(
                [
                    'status' => false,
                    'message' => $this->validator->getErrors()
                ],
                ResponseInterface::HTTP_OK
            );
        }

        $forgot_pass = $this->model->where(['email' => $input['email'], 'token' => $input['token']])->first();
        if (!$forgot_pass) {
            return $this->getResponse(["status" => false, "message" => lang('App.tokenInvalid'), "data" => []], ResponseInterface::HTTP_OK);
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
                    'data' => ['url' => base_url("/login")]
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

    private function getJWTForUser(
        string $emailAddress,
        bool $remember,
        int $responseCode = ResponseInterface::HTTP_OK
    ) {
        try {
            $user = $this->model->findUserByEmailAddress($emailAddress);
            unset($user['password']);

            helper('jwt');

            $group = $this->group->getGroupById($user['user_id']);

            $setSession = [
                'id' => $user['user_id'],
                'email' => $user['email'],
                'username' => $user['username'],
                'fullname' => $user['fullname'],
                'role' => $group['group_id'],
                'active' => $user['is_active'],
                'group' => $group['group_name'],
                'logged_in' => true
            ];
            $this->session->set($setSession);

            if ($remember == true) {
                // 8 Jam
                setcookie("access_token", getSignedJWTForUser($emailAddress), time() + 28800, "/", null, null, true);
            } else {
                // 2 Jam
                setcookie("access_token", getSignedJWTForUser($emailAddress), time() + 7200, "/", null, null, true);
            }
           
            // Update last_logged_in
            $lastLogin = [
                'last_logged_in' => date('Y-m-d H:i:s'),
                'ip_address' => getIPAddress()
            ];
            $this->model->update($user['user_id'], $lastLogin);

            // Save Log
            $this->log->save(['keterangan' => session('fullname') . ' (' . session('email') . ') ' . strtolower(lang('App.do')) . ' Login at: ' . date('Y-m-d H:i:s') . ' on device/s: ' . getUserAgent(), 'user_id' => session('id')]);

            return $this->getResponse(
                [
                    'status' => true,
                    'message' => lang('App.authSuccess'),
                    'data' => $user,
                    'access_token' => getSignedJWTForUser($emailAddress),
                    'appdata' => ['username' => $user, 'token' => getSignedJWTForUser($emailAddress)]
                ]
            );
        } catch (Exception $exception) {
            return $this->getResponse(
                [
                    'status' => false,
                    'message' => $exception->getMessage()
                ],
                $responseCode
            );
        }
    }
}
