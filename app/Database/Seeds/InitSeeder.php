<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Modules\Auth\Models\UserModel;
use App\Modules\Group\Models\GroupModel;
use App\Modules\Group\Models\GroupUserModel;
use App\Modules\Page\Models\PageModel;

class InitSeeder extends Seeder
{
    public function run()
    {
        $dataUser = [
            'email' => 'admin@test.com',
            'username' => 'admin',
            'password' => '12345678',
            'fullname' => 'Administrator',
            'user_type' => 1,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $user = new UserModel();
        $user->save($dataUser);

        $dataSetting = [
            [
                'group_setting' => 'app',
                'variable_setting' => 'app_name',
                'value_setting' => 'Adminku CI4',
                'description_setting' => 'Application Name',
            ],
            [
                'group_setting' => 'image',
                'variable_setting' => 'img_logo',
                'value_setting' => 'assets/images/logo.png',
                'description_setting' => 'Image Logo',
            ]
        ];
        $this->db->table('settings')->insertBatch($dataSetting);

        $dataGroup = [
            'nama_group' => 'Administrator',
            'permission' => 'a:17:{i:0;s:13:"viewDashboard";i:1;s:13:"menuDashboard";i:2;s:8:"menuUser";i:3;s:8:"viewUser";i:4;s:10:"createUser";i:5;s:10:"updateUser";i:6;s:10:"deleteUser";i:7;s:9:"viewGroup";i:8;s:11:"createGroup";i:9;s:11:"updateGroup";i:10;s:11:"deleteGroup";i:11;s:11:"menuSetting";i:12;s:11:"viewSetting";i:13;s:13:"updateSetting";i:14;s:10:"viewBackup";i:15;s:12:"createBackup";i:16;s:12:"deleteBackup";}',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ];
        $user = new GroupModel();
        $user->save($dataGroup);

        $dataGroupUser = [
            'id_user' => 1,
            'id_group' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ];
        $user = new GroupUserModel();
        $user->save($dataGroupUser);

        $dataPages = [
            [
                'page_title' => 'Syarat dan Ketentuan',
                'page_title_en' => 'Terms and Conditions',
                'page_body' => '',
                'page_body_en' => '',
                'active' => 1,
                'slug' => 'terms',
                'id_user' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => null
            ],
            [
                'page_title' => 'Kebijakan Privasi',
                'page_title_en' => 'Privacy Policy',
                'page_body' => '',
                'page_body_en' => '',
                'active' => 1,
                'slug' => 'privacy',
                'id_user' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => null
            ],
            [
                'page_title' => 'Tentang Kami',
                'page_title_en' => 'About Us',
                'page_body' => '',
                'page_body_en' => '',
                'active' => 1,
                'slug' => 'about',
                'id_user' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => null
            ]
        ];
        $pages = new PageModel();
        $pages->insertBatch($dataPages);
    }
}
