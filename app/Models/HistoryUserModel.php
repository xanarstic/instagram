<?php

namespace App\Models;

use CodeIgniter\Model;

class HistoryUserModel extends Model
{
    protected $table = 'history_user';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'activity_type', 'activity_time', 'user_agent'];
}
