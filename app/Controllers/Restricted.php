<?php

namespace  App\Controllers;
/*
PT. GLOBAL ITSHOP PURWOKERTO
Toko Online: ITShop Purwokerto (Tokopedia, Shopee, Bukalapak, Blibli)
Dibuat oleh: Hari Wicaksono, S.Kom
06-2022
*/

class Restricted extends BaseController
{

	public function __construct()
	{
		
	}

    public function index()
	{
        return view('restricted', [
            'title' => 'Restricted! Access Denied'
        ]);
    }

}
