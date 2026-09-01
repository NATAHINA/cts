<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCotationsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('cotations')) {
            return;
        }

        $this->forge->addField([
            'id'                     => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'              => ['type' => 'INT', 'unsigned' => true],
            'numero'                 => ['type' => 'VARCHAR', 'constraint' => 50],
            'client_id'              => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'demande_id'             => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'destination_id'         => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'date_depart'            => ['type' => 'DATE', 'null' => true],
            'date_retour'            => ['type' => 'DATE', 'null' => true],
            'nb_adultes'             => ['type' => 'INT', 'unsigned' => true, 'default' => 1],
            'nb_enfants'             => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'nb_bebes'               => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'devise'                 => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'taux_change'            => ['type' => 'DECIMAL', 'constraint' => '15,6', 'default' => 1],
            'cout_total'             => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'marge_montant'          => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'marge_pourcentage'      => ['type' => 'DECIMAL', 'constraint' => '8,2', 'default' => 0],
            'reduction_montant'      => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'reduction_pourcentage'  => ['type' => 'DECIMAL', 'constraint' => '8,2', 'default' => 0],
            'taxe_montant'           => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'prix_total'             => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'prix_par_personne'      => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'statut'                 => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'brouillon'],
            'date_validite'          => ['type' => 'DATE', 'null' => true],
            'notes_client'           => ['type' => 'TEXT', 'null' => true],
            'notes_interne'          => ['type' => 'TEXT', 'null' => true],
            'created_by'             => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'validated_by'           => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'validated_at'           => ['type' => 'DATETIME', 'null' => true],
            'created_at'             => ['type' => 'DATETIME', 'null' => true],
            'updated_at'             => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->addKey('client_id');
        $this->forge->addKey('demande_id');
        $this->forge->createTable('cotations', false, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        if ($this->db->tableExists('cotations')) {
            $this->forge->dropTable('cotations');
        }
    }
}