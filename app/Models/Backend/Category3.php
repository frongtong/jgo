<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category3 extends Model
{
    use HasFactory;
    protected $table = 'category3';
    protected $fillable = [
        'name_th',
        'name_en',
        'category2_id',
    ];
    public function category2()
    {
        return $this->belongsTo(Category2::class,'category2_id');
    }
}
