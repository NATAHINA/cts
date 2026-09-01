<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAssurancesTable extends Migration
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

            'tenant_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],

            'nom' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],

            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'telephone' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],

            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],

            'adresse' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'site_web' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],

            'statut' => [
                'type'       => 'ENUM',
                'constraint' => ['actif', 'inactif'],
                'default'    => 'actif',
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

        // Clé primaire
        $this->forge->addKey('id', true);

        // Index pour le système multi-tenant
        $this->forge->addKey('tenant_id');

        // Création de la table
        $this->forge->createTable('assurances');
    }

    public function down()
    {
        $this->forge->dropTable('assurances');
    }
}