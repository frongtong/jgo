<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'videos';

    protected $fillable = [
        'title',
        'cover_image_url',
        'youtube_url',
        'main_category_id',
        'sub_category_id',
        'status',
        'published_at'
    ];

    public function mainCategory()
    {
        return $this->belongsTo(
            VideoCategory1::class,
            'main_category_id'
        );
    }

    public function subCategory()
    {
        return $this->belongsTo(
            VideoCategory2::class,
            'sub_category_id'
        );
    }
}