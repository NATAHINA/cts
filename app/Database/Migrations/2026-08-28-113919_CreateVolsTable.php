<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVolsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('vols')) {
            return;
        }

        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'   => ['type' => 'INT', 'unsigned' => true],
            'compagnie'   => ['type' => 'VARCHAR', 'constraint' => 100],
            'num_vol'   => ['type' => 'VARCHAR', 'constraint' => 100],
            'aeroport_depart'         => ['type' => 'VARCHAR', 'constraint' => 150],
            'aeroport_arrivee'         => ['type' => 'VARCHAR', 'constraint' => 100],
            'date_depart'         => ['type' => 'DATE', 'null' => true],
            'date_arrivee'         => ['type' => 'DATE', 'null' => true],
            'prix'         => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'prix_adulte'         => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'prix_enfant'         => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'prix_groupe'         => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'id_fournisseur'   => ['type' => 'INT', 'unsigned' => true],
            'disponibilite'      => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'disponible'],
            'devise_id'         => ['type' => 'INT', 'unsigned' => true],
            'statut'      => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'actif'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->createTable('vols', false, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        if ($this->db->tableExists('vols')) {
            $this->forge->dropTable('vols');
        }
    }
}
