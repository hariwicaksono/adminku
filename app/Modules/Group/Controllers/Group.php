<?php

namespace App\Modules\Group\Controllers;

use App\Controllers\BaseController;
use App\Modules\Group\Models\GroupModel;
use CodeIgniter\I18n\Time;
use App\Libraries\Settings;

class Group extends BaseController
{
    protected $group;
    protected $setting;

    public function __construct()
    {
        //memanggil function di model
        $this->group = new GroupModel();
        $this->setting = new Settings();
    }

    public function index()
    {
        return view('App\Modules\Group\Views/group', [
            'title' => 'Group',
            //'masterPermissions' => unserialize($this->setting->info['permissions']),
            //'permissions' => json_encode(unserialize($this->setting->info['permissions']))
        ]);
    }

    public function edit($id = null)
    {
        $group = $this->group->find($id);
        return view('App\Modules\Group\Views/group_edit', [
            'title' => 'Edit Group: ' . $group['group_name'],
            'id' => $id,
            'group' => $group,
            'permissions' => unserialize($group['permission'])
        ]);
    }

    public function update($id)
	{
		$permission = serialize($this->request->getPost('permission'));
	            
        $data = array(
            'group_name' => $this->request->getPost('group_name'),
            'permission' => $permission,
            'updated_at' => date('Y-m-d H:i:s')
        );

		$this->group->update($id, $data);
		$this->session->setFlashdata('success', 'Data Berhasil Di Update.');
		return redirect()->to('/group');
    }
}
