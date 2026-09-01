<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDemandesTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('demandes')) {
            return;
        }

        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'     => ['type' => 'INT', 'unsigned' => true],
            'client_id'     => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'numero'        => ['type' => 'VARCHAR', 'constraint' => 50],
            'date_demande'  => ['type' => 'DATE', 'null' => true],
            'destination'   => ['type' => 'VARCHAR', 'constraint' => 180, 'null' => true],
            'date_depart'   => ['type' => 'DATE', 'null' => true],
            'date_retour'   => ['type' => 'DATE', 'null' => true],
            'nb_adultes'    => ['type' => 'INT', 'unsigned' => true, 'default' => 1],
            'nb_enfants'    => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'nb_bebes'      => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'budget'        => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => true],
            'devise'        => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'source'        => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'statut'        => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'nouvelle'],
            'notes_client'  => ['type' => 'TEXT', 'null' => true],
            'notes_interne' => ['type' => 'TEXT', 'null' => true],
            'created_by'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->addKey('client_id');
        $this->forge->createTable('demandes', false, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        if ($this->db->tableExists('demandes')) {
            $this->forge->dropTable('demandes');
        }
    }
}