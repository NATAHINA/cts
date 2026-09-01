<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTenantsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('tenants')) {
            return;
        }

        $this->forge->addField([
            'id'                   => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nom_agence'           => ['type' => 'VARCHAR', 'constraint' => 150],
            'slug'                 => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'email_contact'        => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'telephone'            => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
            'adresse'              => ['type' => 'TEXT', 'null' => true],
            'logo'                 => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'nif'                  => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'stat'                 => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'rcs'                  => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'site_web'             => ['type' => 'VARCHAR', 'constraint' => 180, 'null' => true],
            'devise_defaut'        => ['type' => 'VARCHAR', 'constraint' => 5, 'default' => 'MGA'],
            'tva'                  => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
            'prefixe_cotation'     => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'COT'],
            'prefixe_reservation'  => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'RES'],
            'prefixe_facture'      => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'FAC'],
            'conditions_generales' => ['type' => 'TEXT', 'null' => true],
            'pied_page_document'   => ['type' => 'TEXT', 'null' => true],
            'plan'                 => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'basic'],
            'statut'               => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'actif'],
            'created_at'           => ['type' => 'DATETIME', 'null' => true],
            'updated_at'           => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('tenants', false, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        if ($this->db->tableExists('tenants')) {
            $this->forge->dropTable('tenants');
        }
    }
}