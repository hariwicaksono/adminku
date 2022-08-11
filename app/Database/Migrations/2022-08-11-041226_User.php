<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class User extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_user' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'email' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'username' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'password' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'fullname' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'user_type' => [
                'type'           => 'INT',
                'constraint'     => 1,
                'default'        => 0
            ],
            'is_active' => [
                'type'           => 'INT',
                'constraint'     => 1,
                'default'        => 0
            ],
            'token' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => true,
            ],
            'last_logged_in' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => true,
            ],
            'ip_address' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => true,
            ],
            'created_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
            'updated_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
            'deleted_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ]
        ]);
        $this->forge->addPrimaryKey('id_user');
        $this->forge->createTable('users');
    }	

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
