<?php
// app/Database/Migrations/2026-08-30-100003_CreateMomentTags.php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMomentTags extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'moment_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tag_label'  => ['type' => 'VARCHAR', 'constraint' => 100], // ex: [AKADEMIK] #MetodeRiset
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('moment_id', 'moments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('moment_tags');
    }

    public function down()
    {
        $this->forge->dropTable('moment_tags');
    }
}