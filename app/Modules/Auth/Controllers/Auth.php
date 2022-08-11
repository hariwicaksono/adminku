<?php

namespace App\Modules\Auth\Controllers;
/*
IT Shop Purwokerto (Tokopedia, Shopee & Bukalapak)
Dibuat oleh: Hari Wicaksono, S.Kom
06-2022
*/

use App\Controllers\BaseController;
use App\Modules\Auth\Models\LoginModel;

class Auth extends BaseController
{
	public function login()
	{
		if ($this->session->logged_in == true && $this->session->user_type == 1) {return redirect()->to('/dashboard');} 
		if ($this->session->logged_in == true && $this->session->user_type == 2) {return redirect()->to('/dashboard');}
		
		return view('App\Modules\Auth\Views/login', [
			'title' => 'Login',
		]);
	}

	public function register()
	{
		if ($this->session->logged_in == true && $this->session->user_type == 1) {return redirect()->to('/dashboard');} 
		if ($this->session->logged_in == true && $this->session->user_type == 2) {return redirect()->to('/dashboard');}

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

		$user_model = new LoginModel();
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
		$this->session->destroy();
		$this->session->setFlashdata('success', 'Berhasil Logout');
		if (isset($_COOKIE['access_token'])) {
			unset($_COOKIE['access_token']);
			setcookie('access_token', '', time() - 3600, '/'); // empty value and old timestamp
		}
		return redirect()->to('/login');
	}
}
