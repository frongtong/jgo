<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogEmail_News extends Model
{
    protected $table = 'LogEmail_news';
    protected $fillable = [
        'email_user',
        'id_news_new',
        'id_news_old',
        'set_date_time',
        'news_type',
        'email',
        'status',
        'created_by'

    ];

    public function News_new()
    {
        return $this->belongsTo(News_new::class, 'id_news_new', 'id');
    }

    public function News_old()
    {
        return $this->belongsTo(News_old::class, 'id_news_old', 'id');
    }
}
