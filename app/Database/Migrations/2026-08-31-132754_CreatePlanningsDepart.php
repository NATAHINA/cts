<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePlanningsDepart extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'destination_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],

            'date_depart' => [
                'type' => 'DATE',
            ],

            'date_retour' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'heure_depart' => [
                'type' => 'TIME',
                'null' => true,
            ],

            'heure_retour' => [
                'type' => 'TIME',
                'null' => true,
            ],

            'capacite' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],

            'places_vendues' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],

            'prix' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'null'       => true,
            ],

            'devise' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'default'    => 'MGA',
            ],

            'statut' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'planifie',
            ],

            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('destination_id');
        $this->forge->addKey('date_depart');
        $this->forge->addKey('statut');

        $this->forge->addForeignKey(
            'destination_id',
            'destinations',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->forge->createTable('plannings_depart');
    }

    public function down()
    {
        $this->forge->dropTable('plannings_depart', true);
    }
}