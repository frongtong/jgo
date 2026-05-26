<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailHome extends Model
{
    protected $table = 'detail_home';
    protected $fillable = [
        'name_th',
        'name_en',
        'number',
        'unit_th',
        'unit_en',
        'active',

    ];

}
