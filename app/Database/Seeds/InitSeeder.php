<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

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
        $this->db->table('users')->insert($dataUser);

        $dataSetting = [
            [
                'setting_group' => 'app',
                'setting_variable' => 'app_name',
                'setting_value' => 'Adminku CI4',
                'setting_description' => 'Application Name',
            ],
            [
                'setting_group' => 'image',
                'setting_variable' => 'img_logo',
                'setting_value' => 'assets/images/logo.png',
                'setting_description' => 'Image Logo',
            ],
            [
                'setting_group' => 'image',
                'setting_variable' => 'img_background',
                'setting_value' => 'assets/images/test.jpg',
                'setting_description' => 'Background Image',
            ],
            [
                'setting_group' => 'app',
                'setting_variable' => 'navbar_color',
                'setting_value' => 'blue',
                'setting_description' => 'Navbar Color',
            ],
            [
                'setting_group' => 'app',
                'setting_variable' => 'sidebar_color',
                'setting_value' => 'black',
                'setting_description' => 'Sidebar Color',
            ],
            [
                'setting_group' => 'app',
                'setting_variable' => 'company_name',
                'setting_value' => 'Your Company',
                'setting_description' => 'Company Name',
            ],
            [
                'setting_group' => 'app',
                'setting_variable' => 'company_telp',
                'setting_value' => '08123456789',
                'setting_description' => 'Company Telephone',
            ],
            [
                'setting_group' => 'app',
                'setting_variable' => 'company_email',
                'setting_value' => 'info@mail.com',
                'setting_description' => 'Company Email',
            ],
            [
                'setting_group' => 'app',
                'setting_variable' => 'company_address',
                'setting_value' => 'Your Address',
                'setting_description' => 'Company Address',
            ],
            [
                'setting_group' => 'app',
                'setting_variable' => 'company_postalcode',
                'setting_value' => '123456',
                'setting_description' => 'Company Postal Code',
            ]  
        ];
        $this->db->table('settings')->insertBatch($dataSetting);

        $dataGroup = [
            'group_name' => 'Administrator',
            'permission' => 'a:17:{i:0;s:13:"viewDashboard";i:1;s:13:"menuDashboard";i:2;s:8:"menuUser";i:3;s:8:"viewUser";i:4;s:10:"createUser";i:5;s:10:"updateUser";i:6;s:10:"deleteUser";i:7;s:9:"viewGroup";i:8;s:11:"createGroup";i:9;s:11:"updateGroup";i:10;s:11:"deleteGroup";i:11;s:11:"menuSetting";i:12;s:11:"viewSetting";i:13;s:13:"updateSetting";i:14;s:10:"viewBackup";i:15;s:12:"createBackup";i:16;s:12:"deleteBackup";}',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ];
        $this->db->table('groups')->insert($dataGroup);

        $dataGroupUser = [
            'user_id' => 1,
            'group_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ];
        $this->db->table('groups_user')->insert($dataGroupUser);

        $dataPages = [
            [
                'page_title' => 'Syarat dan Ketentuan',
                'page_title_en' => 'Terms and Conditions',
                'page_body' => '',
                'page_body_en' => '',
                'active' => 1,
                'slug' => 'terms',
                'user_id' => 1,
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
                'user_id' => 1,
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
                'user_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => null
            ]
        ];
        $this->db->table('pages')->insertBatch($dataPages);
    }
}
