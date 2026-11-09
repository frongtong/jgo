<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News_new_url extends Model
{
    use HasFactory;
    protected $table = 'news_new_url';
    protected $fillable = [
        'id',
        'id_news_new',
        'url',
        'text_ref',
        'date',
    ];
}
