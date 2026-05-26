<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs_listModel extends Model
{
    use HasFactory;
    protected $table = 'tb_logs_list';
    protected $primaryKey = 'id';
    public $timestamp = true;
}
