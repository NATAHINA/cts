<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCotationLignesTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('cotation_lignes')) {
            return;
        }

        $this->forge->addField([
            'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'         => ['type' => 'INT', 'unsigned' => true],
            'cotation_id'       => ['type' => 'INT', 'unsigned' => true],
            'type_prestation'   => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'prestation_id'     => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'fournisseur_id'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'designation'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'description'       => ['type' => 'TEXT', 'null' => true],
            'quantite'          => ['type' => 'INT', 'unsigned' => true, 'default' => 1],
            'cout_unitaire'     => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'cout_total'        => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'marge_pourcentage' => ['type' => 'DECIMAL', 'constraint' => '8,2', 'default' => 0],
            'marge_montant'     => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'prix_unitaire'     => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'prix_total'        => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'devise'            => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'ordre'             => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->addKey('cotation_id');
        $this->forge->createTable('cotation_lignes', false, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        if ($this->db->tableExists('cotation_lignes')) {
            $this->forge->dropTable('cotation_lignes');
        }
    }
}