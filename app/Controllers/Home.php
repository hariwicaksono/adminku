<?php

namespace App\Controllers;

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

	public function setLanguage()
	{
		$lang = $this->request->uri->getSegments()[1];
		$this->session->set("lang", $lang);
		return redirect()->to(base_url());
	}

	//--------------------------------------------------------------------

}
