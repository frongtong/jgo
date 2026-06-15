<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleCategory2 extends Model
{
    use HasFactory;

    protected $table = 'article_category2';

    protected $fillable = [
        'category1_id',
        'name_th',
        'status',
        'sort'
    ];

    public function category1()
    {
        return $this->belongsTo(
            ArticleCategory1::class,
            'category1_id',
            'id'
        );
    }

    public function articles()
    {
        return $this->hasMany(
            Article::class,
            'article_category2_id',
            'id'
        );
    }
}