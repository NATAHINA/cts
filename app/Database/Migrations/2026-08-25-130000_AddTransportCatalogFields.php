<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTransportCatalogFields extends Migration
{
    public function up()
    {
        $tables = ['vols', 'hotels', 'transferts', 'excursions', 'circuits', 'croisieres'];
        $columns = [
            'fournisseur' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'disponibilite' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'disponible',
            ],
            'prix_adulte' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => true,
            ],
            'prix_enfant' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => true,
            ],
            'prix_groupe' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => true,
            ],
        ];

        foreach ($tables as $table) {
            if (! $this->db->tableExists($table)) {
                continue;
            }

            foreach ($columns as $column => $definition) {
                if (! $this->db->fieldExists($column, $table)) {
                    $this->forge->addColumn($table, [$column => $definition]);
                }
            }
        }
    }

    public function down()
    {
        $tables = ['vols', 'hotels', 'transferts', 'excursions', 'circuits', 'croisieres'];
        $columns = ['fournisseur', 'disponibilite', 'prix_adulte', 'prix_enfant', 'prix_groupe'];

        foreach ($tables as $table) {
            if ($this->db->tableExists($table)) {
                foreach ($columns as $column) {
                    if ($this->db->fieldExists($column, $table)) {
                        $this->forge->dropColumn($table, $column);
                    }
                }
            }
        }
    }
}
