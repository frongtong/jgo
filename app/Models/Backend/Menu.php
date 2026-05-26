<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu_frontend';
    protected $fillable = [
        'name_th',
        'name_en',
        'status',

    ];


}
