<?php

namespace  App\Modules\Dashboard\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Settings;

class Dashboard extends BaseController
{
	protected $setting;

	public function __construct()
	{
		//memanggil Model
		$this->setting = new Settings();
	}


	public function index()
	{
		return view('App\Modules\Dashboard\Views/dashboard', [
			'title' => 'Dashboard',
			'appname' => $this->setting->info['app_name'],
		]);
	}

}
