<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class News_old_image extends Model
{
    use HasFactory;

    protected $table = 'news_old_image';
    protected $fillable = [
        'image',
        'news_old_id',
        'order'
    ];

    public function News_old()
    {
        return $this->belongsTo(News_old::class, 'news_old_id', 'id');
    }
}
