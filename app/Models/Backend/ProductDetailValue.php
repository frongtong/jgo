<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetailValue extends Model
{
    use HasFactory;
    protected $table = 'product_detail_value';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    
    public function product_detail()
    {
        return $this->belongsTo(ProductDetail::class, 'product_detail_id', 'id');
    }
}
