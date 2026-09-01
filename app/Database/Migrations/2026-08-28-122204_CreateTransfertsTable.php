<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransfertsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('transferts')) {
            return;
        }

        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'      => ['type' => 'INT', 'unsigned' => true],
            'destination_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'nom'            => ['type' => 'VARCHAR', 'constraint' => 180],
            'type'           => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'vehicule'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'capacite'       => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'prix'           => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'prix_adulte'    => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'prix_enfant'    => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'prix_groupe'    => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'fournisseur_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'disponibilite'  => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'devise_id'      => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'statut'         => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'actif'],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->addKey('destination_id');
        $this->forge->createTable('transferts', false, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        if ($this->db->tableExists('transferts')) {
            $this->forge->dropTable('transferts');
        }
    }
}