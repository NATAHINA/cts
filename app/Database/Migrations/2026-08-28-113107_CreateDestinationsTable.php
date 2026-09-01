<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDestinationsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('destinations')) {
            return;
        }

        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'   => ['type' => 'INT', 'unsigned' => true],
            'destination_id' => ['type' => 'INT', 'unsigned' => true],
            'nom'         => ['type' => 'VARCHAR', 'constraint' => 150],
            'pays'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'region'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'statut'      => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'actif'],
            'description' => ['type' => 'TEXT', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->createTable('destinations', false, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        if ($this->db->tableExists('destinations')) {
            $this->forge->dropTable('destinations');
        }
    }
}
