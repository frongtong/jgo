<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleCategory1 extends Model
{
    use HasFactory;

    protected $table = 'article_category1';

    protected $fillable = [
        'name_th',
        'status',
        'sort'
    ];

    public function category2()
    {
        return $this->hasMany(
            ArticleCategory2::class,
            'category1_id',
            'id'
        );
    }

    public function articles()
    {
        return $this->hasMany(
            Article::class,
            'article_category1_id',
            'id'
        );
    }
}