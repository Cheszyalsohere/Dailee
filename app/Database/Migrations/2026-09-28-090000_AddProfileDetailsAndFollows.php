<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProfileDetailsAndFollows extends Migration
{
    public function up()
    {
        $profileFields = [
            'location' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'occupation' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'education' => ['type' => 'VARCHAR', 'constraint' => 180, 'null' => true],
            'zodiac_or_interest' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'bio' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ];

        foreach ($profileFields as $field => $definition) {
            if (!$this->db->fieldExists($field, 'users')) {
                $this->forge->addColumn('users', [$field => $definition]);
            }
        }

        if (!$this->db->tableExists('follows')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'follower_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
                'following_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey(['follower_id', 'following_id'], false, true);
            $this->forge->addForeignKey('follower_id', 'users', 'id', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('following_id', 'users', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('follows');
        }
    }

    public function down()
    {
        if ($this->db->tableExists('follows')) {
            $this->forge->dropTable('follows');
        }

        foreach (['location', 'occupation', 'education', 'zodiac_or_interest', 'bio'] as $field) {
            if ($this->db->fieldExists($field, 'users')) {
                $this->forge->dropColumn('users', $field);
            }
        }
    }
}
