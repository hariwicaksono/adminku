<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class GroupUser extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'group_user_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'group_id' => [
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
        $this->forge->addPrimaryKey('group_user_id');
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('group_id', 'groups', 'group_id');
        $this->forge->createTable('groups_user');
    }

    public function down()
    {
        $this->forge->dropTable('groups_user');
    }
}
