<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manager_aboutsModel extends Model
{
    use HasFactory;
    protected $table = 'manager_aboutus';
    protected $fillable = [
        'name_th',
        'name_en',
        'position_th',
        'position_en',
        'image',
    ];
}
