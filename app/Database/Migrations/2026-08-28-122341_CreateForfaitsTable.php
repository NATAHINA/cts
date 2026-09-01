<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateForfaitsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('forfaits')) {
            return;
        }

        $this->forge->addField([
            'id'                     => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'              => ['type' => 'INT', 'unsigned' => true],
            'destination_id'         => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'fournisseur_id'         => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'code'                   => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'nom'                    => ['type' => 'VARCHAR', 'constraint' => 180],
            'description'            => ['type' => 'TEXT', 'null' => true],
            'duree_jours'            => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'duree_nuits'            => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'prix'                   => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'prix_adulte'            => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'prix_enfant'            => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'prix_groupe'            => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'devise_id'              => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'commission_pourcentage' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
            'disponibilite'          => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'statut'                 => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'actif'],
            'created_at'             => ['type' => 'DATETIME', 'null' => true],
            'updated_at'             => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->addKey('destination_id');
        $this->forge->createTable('forfaits', false, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        if ($this->db->tableExists('forfaits')) {
            $this->forge->dropTable('forfaits');
        }
    }
}