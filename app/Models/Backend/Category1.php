<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category1 extends Model
{
    use HasFactory;
    protected $table = 'category1';
    protected $fillable = [
        'name_th',
       
    ];
 
    public function category2()
    {
        return $this->hasMany(Category2::class, 'category1_id');
    }
   
}
