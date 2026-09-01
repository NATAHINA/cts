<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFacturesTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('factures')) {
            return;
        }

        $this->forge->addField([
            'id'              => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'       => ['type' => 'INT', 'unsigned' => true],
            'client_id'       => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'reservation_id'  => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'numero'          => ['type' => 'VARCHAR', 'constraint' => 50],
            'date_facture'    => ['type' => 'DATE', 'null' => true],
            'date_echeance'   => ['type' => 'DATE', 'null' => true],
            'montant_ht'      => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'taxe'            => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'montant_ttc'     => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'montant_paye'    => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'devise'          => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'statut'          => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'brouillon'],
            'notes'           => ['type' => 'TEXT', 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->addKey('client_id');
        $this->forge->addKey('reservation_id');
        $this->forge->createTable('factures', false, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        if ($this->db->tableExists('factures')) {
            $this->forge->dropTable('factures');
        }
    }
}