<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFournisseursTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('fournisseurs')) {
            return;
        }

        $this->forge->addField([
            'id'                     => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tenant_id'              => ['type' => 'INT', 'unsigned' => true],
            'type'                   => ['type' => 'VARCHAR', 'constraint' => 50],
            'nom'                    => ['type' => 'VARCHAR', 'constraint' => 150],
            'contact_nom'            => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'telephone'              => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'email'                  => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'adresse'                => ['type' => 'TEXT', 'null' => true],
            'ville'                  => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'pays'                   => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'devise'                 => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'conditions_paiement'    => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'delai_paiement'         => ['type' => 'INT', 'null' => true],
            'commission_pourcentage' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
            'statut'                 => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'actif'],
            'notes'                  => ['type' => 'TEXT', 'null' => true],
            'created_at'             => ['type' => 'DATETIME', 'null' => true],
            'updated_at'             => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('tenant_id');
        $this->forge->createTable('fournisseurs', false, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        if ($this->db->tableExists('fournisseurs')) {
            $this->forge->dropTable('fournisseurs');
        }
    }
}