<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmupurModel extends Model
{
    use HasFactory;
    protected $table = 'tb_amupur';
    protected $primaryKey = 'id';
    protected $fillable = ['province_code','code','name_th','name_en'];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    public $timestamp = false;
}
