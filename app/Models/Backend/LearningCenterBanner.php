<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningCenterBanner extends Model
{
    use HasFactory;

    protected $table = 'learning_center_banners';

    protected $fillable = [
        'image_url',
        'status',
    ];
}
