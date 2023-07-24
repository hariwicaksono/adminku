<?php

namespace App\Modules\Auth\Controllers;

use App\Controllers\BaseController;
use App\Modules\Auth\Models\UserModel;
use App\Modules\Log\Models\LogModel;

class Auth extends BaseController
{
	protected $log;

	public function __construct()
	{
		$this->log = new LogModel();
	}

	public function login()
	{
		if ($this->session->logged_in == true) {return redirect()->to('/dashboard');} 
		
		return view('App\Modules\Auth\Views/login', [
			'title' => 'Login',
		]);
	}

	public function register()
	{
		if ($this->session->logged_in == true) {return redirect()->to('/dashboard');} 

		return view('App\Modules\Auth\Views/register', [
			'title' => 'Register',
		]);
	}

	public function verifyEmail()
	{
		$input = $this->request->getVar();

		$rules = [
			'email' => [
				'rules'  => 'required',
				'errors' => []
			],
			'token' => [
				'rules'  => 'required',
				'errors' => []
			],
		];

		if (!$this->validate($rules)) {
			return redirect()->to(base_url());
		}

		$user_model = new UserModel();
		$user = $user_model->where(['email' => $input['email'], 'token' => $input['token']])->first();
		$user_data = [
			'active' => 1,
		];
		$user_model->update($user['user_id'], $user_data);
		return redirect()->to(base_url());
	}

	public function passwordReset()
    {
        if (isset($this->session->username)) return redirect()->to(base_url('dashboard'));
        return view('App\Modules\Auth\Views\password/reset', [
			'title' => 'Reset Password',
		]);
    }

	public function passwordChange()
    {
        if (isset($this->session->username)) return redirect()->to(base_url('dashboard'));
        $rules = [
            'email' => [
                'rules'  => 'required',
                'errors' => []
            ],
            'token' => [
                'rules'  => 'required',
                'errors' => []
            ],
        ];
        if (!$this->validate($rules)) {
            return redirect()->to(base_url());
        }
        $data = $this->request->getVar();
		$data['title'] = 'Change Password';
        return view('App\Modules\Auth\Views\password/change', $data);
    }

	public function logout()
	{
		// Data Session
		$data = ['id', 'email', 'username', 'fullname', 'role', 'active', 'group', 'logged_in'];
		// Save Log
		$this->log->save(['keterangan' => session('fullname') . ' (' . session('email') . ') ' . strtolower(lang('App.do')) . ' Logout at: ' . date('Y-m-d H:i:s'), 'user_id' => session('id') ]);
		// Hapus session data
		$this->session->remove($data);
		$this->session->setFlashdata('success', 'You have successfully logged out');
		// Hapus Cookie access_token
		if (isset($_COOKIE['access_token'])) {
			unset($_COOKIE['access_token']);
			setcookie('access_token', '', time() - 3600, '/'); // empty value and old timestamp
		}
		return redirect()->to('/login');
	}
}
