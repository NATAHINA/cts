<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReservationsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('reservations')) {
            return;
        }

        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'      => ['type' => 'INT', 'unsigned' => true],
            'numero'         => ['type' => 'VARCHAR', 'constraint' => 50],
            'cotation_id'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'client_id'      => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'destination_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'date_depart'    => ['type' => 'DATE', 'null' => true],
            'date_retour'    => ['type' => 'DATE', 'null' => true],
            'nb_adultes'     => ['type' => 'INT', 'unsigned' => true, 'default' => 1],
            'nb_enfants'     => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'nb_bebes'       => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'devise'         => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'montant_total'  => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'statut'         => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'en_attente'],
            'notes_client'   => ['type' => 'TEXT', 'null' => true],
            'notes_interne'  => ['type' => 'TEXT', 'null' => true],
            'created_by'     => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->addKey('client_id');
        $this->forge->addKey('cotation_id');
        $this->forge->createTable('reservations', false, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        if ($this->db->tableExists('reservations')) {
            $this->forge->dropTable('reservations');
        }
    }
}