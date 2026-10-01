<?php
// app/Database/Migrations/2026-08-30-100001_CreateAcademicSchedule.php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAcademicSchedule extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'title'       => ['type' => 'VARCHAR', 'constraint' => 150],
            'category'    => ['type' => 'VARCHAR', 'constraint' => 50], // ex: AKADEMIK, ORGANISASI
            'due_date'    => ['type' => 'DATETIME'],
            'is_done'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('academic_schedule');
    }

    public function down()
    {
        $this->forge->dropTable('academic_schedule');
    }
}