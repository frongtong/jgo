<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category2 extends Model
{
    use HasFactory;
    protected $table = 'category2';
    protected $fillable = [
        'name_th',
        'name_en',
        'category1_id',
    ];
    public function category1()
    {
        return $this->belongsTo(Category1::class,'category1_id');
    }
    public function category3()
    {
        return $this->hasMany(Category3::class, 'category2_id');
    }
    public function category2_link()
    {
        return $this->hasMany(Category2_link::class, 'category2_id');
    }
}
