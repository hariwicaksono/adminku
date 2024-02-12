<?php

namespace  App\Modules\Dashboard\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Settings;
use App\Modules\Backup\Models\BackupModel;

class Dashboard extends BaseController
{
	protected $setting;
	protected $backup;

	public function __construct()
	{
		//memanggil Model
		$this->setting = new Settings();
		$this->backup = new BackupModel();
	}


	public function index()
	{
		return view('App\Modules\Dashboard\Views/dashboard', [
			'title' => 'Dashboard',
			'appname' => $this->setting->info['app_name'],
			'getBackups' => $this->backup->getTodayBackups()
		]);
	}

}
