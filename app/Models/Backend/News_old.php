<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class News_old extends Model
{
    use HasFactory;

    protected $table = 'news_old';
    protected $fillable = [
        'start',
        'end',
        'title_th',
        'title_en',
        'video',
        'description_th',
        'description_en',
        'logo_image',
        'news_category_id',
        'status',
        'type_banner',
        'cover',
    ];
    public function NewsCategory()
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id', 'id');
    }
    public function News_old_image()
    {
        return $this->hasmany(News_old_image::class, 'news_old_id', 'id');
    }

}
