<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoCategory2 extends Model
{
    use HasFactory;
    protected $table = 'vocabulary_category2';
    protected $fillable = [
        'name_th',
        'category1_id',
    ];
    public function mainCategory()
    {
        return $this->belongsTo(VoCategory1::class,'category1_id');
    }
      public function vocabularies()
    {
        return $this->hasMany(
            Vocabulary::class,
            'sub_category_id'
        );
    }
  
}
