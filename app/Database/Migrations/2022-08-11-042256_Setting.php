<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Setting extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_setting' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'group_setting' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'variable_setting' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'value_setting' => [
                'type'           => 'TEXT',
            ],
            'description_setting' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'updated_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id_setting');
        $this->forge->createTable('settings');
    }

    public function down()
    {
        $this->forge->dropTable('settings');
    }
}
