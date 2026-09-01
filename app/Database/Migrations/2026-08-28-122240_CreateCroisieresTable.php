<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCroisieresTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('croisieres')) {
            return;
        }

        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'      => ['type' => 'INT', 'unsigned' => true],
            'compagnie'      => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'nom'            => ['type' => 'VARCHAR', 'constraint' => 180],
            'itineraire'     => ['type' => 'TEXT', 'null' => true],
            'duree_jours'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'date_depart'    => ['type' => 'DATE', 'null' => true],
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
        $this->forge->createTable('croisieres', false, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        if ($this->db->tableExists('croisieres')) {
            $this->forge->dropTable('croisieres');
        }
    }
}