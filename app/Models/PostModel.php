<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table      = 'posts';
    protected $primaryKey = 'id_post';
    protected $allowedFields = ['user_id', 'media', 'caption', 'created_at'];
}
