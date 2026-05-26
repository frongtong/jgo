<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    use HasFactory;
    
    protected $table = 'tb_role';
    protected $primaryKey = 'id';
    protected $fillable = ['name','detail','status','created','updated'];
    public $timestamp = true;
}
