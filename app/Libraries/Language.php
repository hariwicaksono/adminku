<?php

namespace App\Libraries;
/*
PT ITSHOP BISNIS DIGITAL
Website: https://itshop.biz.id
Toko Online: ITSHOP Purwokerto (Tokopedia.com/itshoppwt, Shopee.co.id/itshoppwt, Toco.id/store/itshop-purwokerto)
Dibuat oleh: Hari Wicaksono, S.Kom
02-2024
*/

class Language
{
	protected $htmlLang = 'en';
	protected $siteLang = 'en-US';

	public function __construct()
	{
		$lang = session()->get('lang') ?? config('App')->defaultLocale;

		if ($lang === 'id') {
			$this->htmlLang = 'id';
			$this->siteLang = 'id-ID';
		} else {
			$this->htmlLang = 'en';
			$this->siteLang = 'en-US';
		}
	}

	public function getHtmlLang(): string
	{
		return $this->htmlLang;
	}

	public function getSiteLang(): string
	{
		return $this->siteLang;
	}
}

