<?php

namespace  App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Settings;

class Home extends BaseController
{
	protected $setting;

	public function __construct()
	{
		//memanggil Model
		$this->setting = new Settings();
	}

	public function index()
	{
		return view('home', [
			'title' => 'Home',
		]);
	}
}
