<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class News_new_image extends Model
{
    use HasFactory;

    protected $table = 'news_new_image';
    protected $fillable = [
        'image',
        'news_new_id',
        'order',
    ];

    public function News_new()
    {
        return $this->belongsTo(News_new::class, 'news_new_id', 'id');
    }
}
