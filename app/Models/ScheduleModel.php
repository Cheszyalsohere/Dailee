<?php
namespace App\Models;
use CodeIgniter\Model;

class ScheduleModel extends Model
{
    protected $table            = 'schedules';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['user_id', 'title', 'category', 'status', 'start_time', 'end_time'];
    protected $useTimestamps    = false;
}