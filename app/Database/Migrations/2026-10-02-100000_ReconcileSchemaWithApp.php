<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Menyamakan skema dengan kolom dan tabel yang benar-benar dipakai controller.
 * Idempotent: aman dijalankan di database kosong maupun yang sudah punya sebagian tabel.
 */
class ReconcileSchemaWithApp extends Migration
{
    private const ID = ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true];
    private const FK = ['type' => 'INT', 'constraint' => 11, 'unsigned' => true];

    public function up()
    {
        $this->reconcileUsers();
        $this->createSchedules();
        $this->reconcileMoments();
        $this->createFriendships();
        $this->createChats();
        $this->createPostTables();
    }

    public function down()
    {
        foreach (['post_shares', 'post_comments', 'post_reactions', 'chats', 'friendships', 'schedules'] as $table) {
            $this->forge->dropTable($table, true);
        }
        // Perubahan kolom pada users dan moments sengaja tidak dibalik.
    }

    private function reconcileUsers(): void
    {
        if (! $this->db->fieldExists('nama_lengkap', 'users')) {
            $this->forge->addColumn('users', [
                'nama_lengkap' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'after' => 'id'],
            ]);
            if ($this->db->fieldExists('name', 'users')) {
                $this->db->query('UPDATE users SET nama_lengkap = name');
            }
        }

        // Registrasi tidak mengisi kolom "name", jadi harus boleh kosong.
        if ($this->db->fieldExists('name', 'users')) {
            $this->forge->modifyColumn('users', [
                'name' => ['name' => 'name', 'type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            ]);
        }

        $this->db->query("ALTER TABLE users MODIFY role ENUM('admin','user','mahasiswa') NOT NULL DEFAULT 'user'");
        $this->db->query("UPDATE users SET role = 'user' WHERE role = 'mahasiswa'");
        $this->db->query("ALTER TABLE users MODIFY role ENUM('admin','user') NOT NULL DEFAULT 'user'");
    }

    private function createSchedules(): void
    {
        if ($this->db->tableExists('schedules')) {
            return;
        }

        $this->forge->addField([
            'id'            => self::ID,
            'user_id'       => self::FK,
            'title'         => ['type' => 'VARCHAR', 'constraint' => 150],
            'category'      => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'status'        => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'To Do'],
            'start_time'    => ['type' => 'DATETIME', 'null' => true],
            'end_time'      => ['type' => 'DATETIME', 'null' => true],
            'alert_time'    => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'sync_calendar' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['user_id', 'start_time']);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('schedules');
    }

    private function reconcileMoments(): void
    {
        $new = [
            'main_image'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'inset_image' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'mood'        => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'Happy'],
            'schedule_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'visibility'  => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'circle'],
        ];
        foreach ($new as $field => $definition) {
            if (! $this->db->fieldExists($field, 'moments')) {
                $this->forge->addColumn('moments', [$field => $definition]);
            }
        }

        // Kolom lama dari migration awal; controller sekarang tidak mengisinya.
        $relax = [
            'photo_path' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'taken_at'   => ['type' => 'DATETIME', 'null' => true],
        ];
        foreach ($relax as $field => $definition) {
            if ($this->db->fieldExists($field, 'moments')) {
                $this->forge->modifyColumn('moments', [$field => ['name' => $field] + $definition]);
            }
        }
    }

    private function createFriendships(): void
    {
        if ($this->db->tableExists('friendships')) {
            return;
        }

        $this->forge->addField([
            'id'         => self::ID,
            'user_id'    => self::FK,
            'friend_id'  => self::FK,
            'status'     => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pending'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['user_id', 'friend_id']);
        $this->forge->addKey('friend_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('friend_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('friendships');
    }

    private function createChats(): void
    {
        if ($this->db->tableExists('chats')) {
            return;
        }

        $this->forge->addField([
            'id'          => self::ID,
            'sender_id'   => self::FK,
            'receiver_id' => self::FK,
            'message'     => ['type' => 'TEXT'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['sender_id', 'receiver_id', 'created_at']);
        $this->forge->addKey('receiver_id');
        $this->forge->addForeignKey('sender_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('receiver_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('chats');
    }

    private function createPostTables(): void
    {
        $tables = [
            'post_reactions' => ['type' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'like']],
            'post_comments'  => ['comment' => ['type' => 'TEXT']],
            'post_shares'    => [],
        ];

        foreach ($tables as $table => $extra) {
            if ($this->db->tableExists($table)) {
                continue;
            }

            $this->forge->addField([
                'id'         => self::ID,
                'post_id'    => self::FK,
                'user_id'    => self::FK,
            ] + $extra + [
                'created_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('post_id');
            if ($table === 'post_reactions') {
                $this->forge->addUniqueKey(['post_id', 'user_id']);
            } else {
                $this->forge->addKey('user_id');
            }
            $this->forge->addForeignKey('post_id', 'moments', 'id', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable($table);
        }
    }
}
