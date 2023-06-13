<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class GroupUser extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_group_user' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'id_user' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'id_group' => [
                'type'           => 'INT',
                'constraint'     => 11,
            ],
            'created_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
            'updated_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id_group_user');
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_group', 'groups', 'id_group');
        $this->forge->createTable('groups_user');
    }

    public function down()
    {
        $this->forge->dropTable('groups_user');
    }
}
