<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Standardproduct extends Model
{
    use HasFactory;
    protected $table = 'standardproduct';
    protected $fillable = [
        'name_th',
        'image',
    ];
}
