<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaiementsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('paiements')) {
            return;
        }

        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'      => ['type' => 'INT', 'unsigned' => true],
            'reservation_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'client_id'      => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'reference'      => ['type' => 'VARCHAR', 'constraint' => 50],
            'date_paiement'  => ['type' => 'DATE', 'null' => true],
            'montant'        => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'devise'         => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'mode_paiement'  => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'statut'         => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'valide'],
            'notes'          => ['type' => 'TEXT', 'null' => true],
            'created_by'     => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->addKey('reservation_id');
        $this->forge->addKey('client_id');
        $this->forge->createTable('paiements', false, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        if ($this->db->tableExists('paiements')) {
            $this->forge->dropTable('paiements');
        }
    }
}