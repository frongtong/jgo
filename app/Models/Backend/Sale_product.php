<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class Sale_product extends Model
{
    use HasFactory;

    protected $table = 'sale_product';
    protected $fillable = [
        'image',
        'url',
        'product_id',
        'big_product_id',
    ];

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function Bigproduct()
    {
        return $this->belongsTo(Product::class, 'big_product_id', 'id');
    }
}
