<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class News_new extends Model
{
    use HasFactory;

    protected $table = 'news_new';
    protected $fillable = [
        'start',
        'end',
        'title_th',
        'title_en',
        'video',
        'description_th',
        'description_en',
        'descriptionshort_th',
        'descriptionshort_en',
        'date_start_show',
        'date_end_show',
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
    public function News_new_image()
    {
        return $this->hasmany(News_new_image::class, 'news_new_id', 'id');
    }

}
