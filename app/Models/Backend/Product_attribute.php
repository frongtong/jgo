<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_attribute extends Model
{
    use HasFactory;
    protected $table = 'product_attribute';
    protected $fillable = [
        'product_id',
        'attribute_id',
        'img',
        'name_th',
        'name_en',
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function AttributeModel()
    {
        return $this->belongsTo(AttributeModel::class, 'attribute_id', 'id');
    }
}