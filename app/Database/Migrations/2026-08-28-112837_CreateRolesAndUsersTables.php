<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRolesAndUsersTables extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('roles')) {
            $this->forge->addField([
                'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'tenant_id'   => ['type' => 'INT', 'unsigned' => true, 'null' => true],
                'code'        => ['type' => 'VARCHAR', 'constraint' => 50],
                'libelle'     => ['type' => 'VARCHAR', 'constraint' => 100],
                'description' => ['type' => 'TEXT', 'null' => true],
                'created_at'  => ['type' => 'DATETIME', 'null' => true],
                'updated_at'  => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('tenant_id');
            $this->forge->addUniqueKey(['tenant_id', 'code']);
            $this->forge->createTable('roles', false, ['ENGINE' => 'InnoDB']);
        }

        if (! $this->db->tableExists('users')) {
            $this->forge->addField([
                'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'tenant_id'      => ['type' => 'INT', 'unsigned' => true],
                'role_id'        => ['type' => 'INT', 'unsigned' => true, 'null' => true],
                'nom'            => ['type' => 'VARCHAR', 'constraint' => 100],
                'prenom'         => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                'email'          => ['type' => 'VARCHAR', 'constraint' => 150],
                'telephone'      => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
                'password'       => ['type' => 'VARCHAR', 'constraint' => 255],
                'actif'          => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
                'dernier_login'  => ['type' => 'DATETIME', 'null' => true],
                'created_at'     => ['type' => 'DATETIME', 'null' => true],
                'updated_at'     => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('tenant_id');
            $this->forge->addUniqueKey(['tenant_id', 'email']);
            $this->forge->createTable('users', false, ['ENGINE' => 'InnoDB']);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('users')) {
            $this->forge->dropTable('users');
        }
        if ($this->db->tableExists('roles')) {
            $this->forge->dropTable('roles');
        }
    }
}