<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRolesAndPermissions extends Migration
{
    public function up()
    {
        // Table: roles
        $this->forge->addField([
            'role_id'   => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name'      => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'created_at'=> ['type' => 'DATETIME', 'null' => true],
            'updated_at'=> ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('role_id', true);
        $this->forge->createTable('roles');

        // Table: permissions
        $this->forge->addField([
            'permission_id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('permission_id', true);
        $this->forge->createTable('permissions');

        // Table: role_user
        $this->forge->addField([
            'role_id' => ['type' => 'INT', 'unsigned' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
        ]);
        $this->forge->addKey(['role_id', 'user_id'], true); // composite PK
        $this->forge->addForeignKey('role_id', 'roles', 'role_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('role_user');

        // Table: permission_role
        $this->forge->addField([
            'permission_id' => ['type' => 'INT', 'unsigned' => true],
            'role_id'       => ['type' => 'INT', 'unsigned' => true],
        ]);
        $this->forge->addKey(['permission_id', 'role_id'], true); // composite PK
        $this->forge->addForeignKey('permission_id', 'permissions', 'permission_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('role_id', 'roles', 'role_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('permission_role');
    }

    public function down()
    {
        $this->forge->dropTable('permission_role', true);
        $this->forge->dropTable('role_user', true);
        $this->forge->dropTable('permissions', true);
        $this->forge->dropTable('roles', true);
    }
}
