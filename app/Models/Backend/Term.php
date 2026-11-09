<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;
    protected $table = 'term';
    protected $fillable = [
        'head_th',
        'head_en',
        'description_th',
        'description_en',
    ];
}
