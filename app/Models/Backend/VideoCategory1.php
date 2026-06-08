<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class VideoCategory1 extends Model
{
    protected $table = 'video_category1';

    protected $fillable = [
        'name_th',
        'status'
    ];

    public function subCategory()
    {
        return $this->hasMany(
            VideoCategory2::class,
            'category1_id'
        );
    }
}
