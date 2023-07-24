<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Page extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'page_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'page_title' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'page_title_en' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'page_body' => [
                'type'           => 'LONGTEXT',
            ],
            'page_body_en' => [
                'type'           => 'LONGTEXT',
            ],
            'active' => [
                'type'           => 'INT',
                'constraint'     => 11,
            ],
            'slug' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
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
        $this->forge->addPrimaryKey('page_id');
        $this->forge->createTable('pages');
    }

    public function down()
    {
        $this->forge->dropTable('pages');
    }
}
