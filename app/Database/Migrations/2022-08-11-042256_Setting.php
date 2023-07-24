<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Setting extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'setting_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'setting_group' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'setting_variable' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'setting_value' => [
                'type'           => 'TEXT',
            ],
            'setting_description' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'updated_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
        ]);
        $this->forge->addPrimaryKey('setting_id');
        $this->forge->createTable('settings');
    }

    public function down()
    {
        $this->forge->dropTable('settings');
    }
}
