<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Modules\Auth\Models\UserModel;

class InitSeeder extends Seeder
{
    public function run()
    {
        $dataUser = [
            'email'     => 'admin@test.com',
            'username'  => 'admin',
            'password'  => '12345678',
            'fullname'  => 'Administrator',
            'user_type' => 1,
            'is_active' => 1,
            'created_at'=> date('Y-m-d H:i:s')
        ];
        $user = new UserModel();
        $user->save($dataUser);

        $dataSetting = [
            [
                'group_setting'         => 'app',
                'variable_setting'      => 'app_name',
                'value_setting'         => 'Adminku CI4',
                'description_setting'  => 'Application Name',
            ]
        ];
        $this->db->table('settings')->insertBatch($dataSetting);
    }
}