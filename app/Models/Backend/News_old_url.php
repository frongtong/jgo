<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News_old_url extends Model
{
    use HasFactory;
    protected $table = 'news_old_url';
    protected $fillable = [
        'id',
        'id_news_old',
        'url',
        'text_ref',
    ];
    
}
