<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotifications extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('notifications')) {
            return;
        }

        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'recipient_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'actor_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'type'         => ['type' => 'VARCHAR', 'constraint' => 30],
            'entity_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'body'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'is_read'      => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['recipient_id', 'is_read', 'created_at']);
        $this->forge->addKey('actor_id');
        $this->forge->addForeignKey('recipient_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('actor_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('notifications');
    }

    public function down()
    {
        $this->forge->dropTable('notifications', true);
    }
}
