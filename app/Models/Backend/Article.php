<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $table = 'article';

    protected $fillable = [
        'title',
        'slug',
        'cover_image_url',
        'banner_image_url',
        'short_description',
        'description',
        'article_category1_id',
        'article_category2_id',
        'published_at',
        'status',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keyword'
    ];
    public function mainCategory()
{
    return $this->belongsTo(
        ArticleCategory1::class,
        'article_category1_id'
    );
}

public function subCategory()
{
    return $this->belongsTo(
        ArticleCategory2::class,
        'article_category2_id'
    );
}
}
