<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRestaurantsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('restaurants')) {
            return;
        }

        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'      => ['type' => 'INT', 'unsigned' => true],
            'destination_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'fournisseur_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'nom'            => ['type' => 'VARCHAR', 'constraint' => 180],
            'type_cuisine'   => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'adresse'        => ['type' => 'TEXT', 'null' => true],
            'telephone'      => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
            'email'          => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'description'    => ['type' => 'TEXT', 'null' => true],
            'prix_moyen'     => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'devise'         => ['type' => 'VARCHAR', 'constraint' => 5, 'default' => 'MGA'],
            'note'           => ['type' => 'DECIMAL', 'constraint' => '2,1', 'null' => true],
            'actif'          => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->addKey('destination_id');
        $this->forge->createTable('restaurants', false, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        if ($this->db->tableExists('restaurants')) {
            $this->forge->dropTable('restaurants');
        }
    }
}