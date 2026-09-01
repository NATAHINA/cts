<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClientsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('clients')) {
            return;
        }

        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'   => ['type' => 'INT', 'unsigned' => true],
            'type_client' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'nom'         => ['type' => 'VARCHAR', 'constraint' => 150],
            'prenom'      => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'entreprise'  => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'telephone'   => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'email'       => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'adresse'     => ['type' => 'TEXT', 'null' => true],
            'ville'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'pays'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'nationalite' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'statut'      => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'actif'],
            'notes'       => ['type' => 'TEXT', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->createTable('clients', false, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        if ($this->db->tableExists('clients')) {
            $this->forge->dropTable('clients');
        }
    }
}