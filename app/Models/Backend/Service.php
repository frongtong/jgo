<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $table = 'service';
    protected $fillable = [
        'banner',
        'title_th',
        'title_en',
        'content_th',
        'content_en',
    ];

}
