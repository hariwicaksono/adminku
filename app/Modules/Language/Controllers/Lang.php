<?php

namespace  App\Modules\Language\Controllers;

use App\Controllers\BaseController;

class Lang extends BaseController
{
	public function setLanguage()
	{
		$lang = $this->request->getUri()->getSegments()[1];
		$this->session->set("lang", $lang);
		return redirect()->back()->with('success', 'Language successfully changed to ' . $lang);
	}
}
