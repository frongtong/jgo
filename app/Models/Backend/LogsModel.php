<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogsModel extends Model
{
    use HasFactory;
    protected $table = 'tb_logs';
    protected $primaryKey = 'id';
    public $timestamp = true;
}
