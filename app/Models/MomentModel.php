<?php
namespace App\Models;
use CodeIgniter\Model;

class MomentModel extends Model
{
    protected $table            = 'moments';
    protected $primaryKey       = 'id';
    // Field sesuai rancangan metadata[cite: 1]
    protected $allowedFields    = ['schedule_id', 'image_path', 'temporal_metadata', 'contextual_provenance', 'technical_metadata', 'subject_tags'];
    protected $useTimestamps    = false; 
}