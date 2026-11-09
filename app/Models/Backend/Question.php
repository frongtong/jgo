<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $table = 'question';
    protected $fillable = [
        'head_th',
        'head_en',
        'description_th',
        'description_en',
        'status',
        'sort',
    ];
}
