<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPackingSize extends Model
{
    use HasFactory;
    protected $table = 'product_packing_size';

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
