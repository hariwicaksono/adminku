<?php

namespace  App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Settings;
use App\Modules\Page\Models\PageModel;

class Home extends BaseController
{
	protected $setting;
	protected $page;

	public function __construct()
	{
		//memanggil Model
		$this->setting = new Settings();
		$this->page = new PageModel();
	}

	public function index(): string
	{
		return view('home', [
			'title' => 'Home',
		]);
	}

	public function sitemap()
	{
		$this->response->setHeader('Content-Type', 'text/xml;charset=UTF-8'); 
		return view('sitemap', [
			'title' => 'Sitemap',
			'pages' => $this->page->orderBy('page_id', 'DESC')->findAll(),
		]);
	}
}
