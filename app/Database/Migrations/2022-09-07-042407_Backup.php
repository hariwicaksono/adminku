<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Backup extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'backup_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'file_name' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => true,
            ],
            'file_path' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => true,
            ],
            'created_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
        ]);
        $this->forge->addPrimaryKey('backup_id');
        $this->forge->createTable('backups');
    }

    public function down()
    {
        $this->forge->dropTable('backups');
    }
}
