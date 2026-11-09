<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class Text_product extends Model
{
    use HasFactory;

    protected $table = 'text_product';
    protected $fillable = [
        'header_th',
        'header_en',
        'description_th',
        'description_en',
        'product_id',
    ];

    public function News_old()
    {
        return $this->belongsTo(News_old::class, 'news_old_id', 'id');
    }
    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
