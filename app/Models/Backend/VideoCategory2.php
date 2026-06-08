<?php
namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class VideoCategory2 extends Model
{
    protected $table = 'video_category2';

    protected $fillable = [
        'category1_id',
        'name_th',
        'status'
    ];

    public function category1()
    {
        return $this->belongsTo(
            VideoCategory1::class,
            'category1_id'
        );
    }
}