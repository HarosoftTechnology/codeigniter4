<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRolePermissions extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'role_id'       => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'permission_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
        ]);
        $this->forge->addKey(['role_id', 'permission_id'], true);
        $this->forge->createTable('role_permissions');
    }

    public function down()
    {
        $this->forge->dropTable('role_permissions');
    }
}
