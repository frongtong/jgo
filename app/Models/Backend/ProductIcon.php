<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductIcon extends Model
{
    use HasFactory;
    protected $table = 'product_icon';
    protected $fillable = [
        'product_id',
        'image',
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
