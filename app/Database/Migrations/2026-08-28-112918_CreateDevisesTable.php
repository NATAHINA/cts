<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDevisesTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('devises')) {
            return;
        }

        $this->forge->addField([
            'id'      => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'code'    => ['type' => 'VARCHAR', 'constraint' => 5],
            'nom'     => ['type' => 'VARCHAR', 'constraint' => 50],
            'symbole' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('code');
        $this->forge->createTable('devises', false, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        if ($this->db->tableExists('devises')) {
            $this->forge->dropTable('devises');
        }
    }
}