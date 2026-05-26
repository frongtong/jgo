<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class Category2_link extends Model
{
    use HasFactory;

    protected $table = 'category2_link';
    protected $fillable = [
        'category2_id',
        'image',
        'url',
    ];

    public function category2()
    {
        return $this->belongsTo(Category2::class, 'category2_id', 'id');
    }

}
