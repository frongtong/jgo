<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyFurther extends Model
{
    use HasFactory;
    protected $table = 'studyfurther';

    protected $fillable = [
        'title',
        'cover_image_url',
        'short_description',
        'banner_image_url',
        'description',
    ];
}
