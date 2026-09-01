<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHotelsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('hotels')) {
            return;
        }

        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'   => ['type' => 'INT', 'unsigned' => true],
            'destination_id'   => ['type' => 'INT', 'unsigned' => true],
            'fournisseur_id'   => ['type' => 'INT', 'unsigned' => true],
            'nom'         => ['type' => 'VARCHAR', 'constraint' => 150],
            'categorie'         => ['type' => 'VARCHAR', 'constraint' => 100],
            'adresse'         => ['type' => 'VARCHAR', 'constraint' => 150],
            'prix_nuit'         => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'prix_adulte'         => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'prix_enfant'         => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'prix_groupe'         => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'disponibilite'      => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'disponible'],
            'devise_id'         => ['type' => 'INT', 'unsigned' => true],
            'statut'      => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'actif'],
            'description' => ['type' => 'TEXT', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->createTable('hotels', false, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        if ($this->db->tableExists('hotels')) {
            $this->forge->dropTable('hotels');
        }
    }
}
