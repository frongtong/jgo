<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'product';
    protected $fillable = [
        'category1_id',
        'brand_id',
        'name_th',
        'name_en',
        'description_th',
        'description_en',
        'product_age_th',
        'product_age_en',
        'suitable_for_th',
        'suitable_for_en',
        'status',
        'sort',
    ];

    public function category1()
    {
        return $this->belongsTo(Category1::class, 'category1_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(brand::class,'brand_id');
    }

   public function brand_2()
    {
        return $this->belongsTo(Brand::class,'brand_id','id');
    }
    public function product_image()
    {
        return $this->hasMany(Product_image::class, 'product_id', 'id');
    }

    public function product_attribute()
    {
        return $this->hasMany(Product_attribute::class, 'product_id', 'id');
    }


    public function product_icon()
    {
        return $this->hasMany(ProductIcon::class, 'product_id', 'id');
    }

    public function product_packing_size()
    {
        return $this->hasMany(ProductPackingSize::class, 'product_id', 'id');
    }

    public function product_detail_value()
    {
        return $this->hasMany(ProductDetailValue::class, 'product_id', 'id');
    }

    public function product_link()
    {
        return $this->hasMany(Product_link::class, 'product_id', 'id');
    }

    public function sale_product()
    {
        return $this->hasMany(Sale_product::class, 'product_id', 'id');
    }
}
