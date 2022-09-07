<?php

namespace  App\Modules\Language\Controllers;

use App\Controllers\BaseController;

class Lang extends BaseController
{
	public function setLanguage()
	{
		$lang = $this->request->uri->getSegments()[1];
		$this->session->set("lang", $lang);
		return redirect()->to(base_url());
	}
}
