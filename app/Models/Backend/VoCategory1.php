<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoCategory1 extends Model
{
    use HasFactory;
    protected $table = 'vocabulary_category1';
    protected $fillable = [
        'name_th',
    ];
 
    public function subCategory()
    {
        return $this->hasMany(VoCategory2::class, 'category1_id');
    }
   public function vocabularies()
    {
        return $this->hasMany(
            Vocabulary::class,
            'main_category_id'
        );
    }
    
}
