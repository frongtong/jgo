<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class Product_link extends Model
{
    use HasFactory;

    protected $table = 'product_link';
    protected $fillable = [
        'image',
        'url',
        'product_id',
        'big_product_id'
    ];

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    
    public function MainProduct()
    {
        return $this->hasMany(Product::class, 'big_product_id', 'id');
    }
    
}
