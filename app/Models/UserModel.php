<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['username', 'password', 'role', 'is_active'];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';

    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    public function countUsers()
    {
        return $this->countAllResults();
    }

    public function getUsersWithRole($role)
    {
        return $this->where('role', $role)->findAll();
    }

    public function getAllUsers($limit = 0, $offset = 0)
    {
        $builder = $this->db->table($this->table);

        if ($limit > 0) {
            $builder->limit($limit, $offset);
        }

        return $builder->orderBy('id', 'ASC')->get()->getResultArray();
    }
}
