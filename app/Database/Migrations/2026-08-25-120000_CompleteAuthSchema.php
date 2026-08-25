<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CompleteAuthSchema extends Migration
{
    public function up()
    {
        $tenantColumns = [
            'nom_agence' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 180,
                'null' => true,
            ],
            'email_contact' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'telephone' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'adresse' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'logo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'plan' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'essai',
            ],
            'statut' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => 'actif',
            ],
        ];

        foreach ($tenantColumns as $column => $definition) {
            if (! $this->db->fieldExists($column, 'tenants')) {
                $this->forge->addColumn('tenants', [$column => $definition]);
            }
        }

        $userColumns = [
            'prenom' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'role' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'default' => 'admin',
            ],
            'statut' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => 'actif',
            ],
            'derniere_connexion' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];

        foreach ($userColumns as $column => $definition) {
            if (! $this->db->fieldExists($column, 'users')) {
                $this->forge->addColumn('users', [$column => $definition]);
            }
        }
    }

    public function down()
    {
        $this->forge->dropColumn('tenants', [
            'nom_agence', 'slug', 'email_contact', 'telephone',
            'adresse', 'logo', 'plan', 'statut',
        ]);
        $this->forge->dropColumn('users', [
            'prenom', 'role', 'statut', 'derniere_connexion',
        ]);
    }
}