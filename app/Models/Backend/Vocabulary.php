<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vocabulary extends Model
{
    use HasFactory;

    protected $table = 'vocabulary';

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'cover_image_url',
        'pdf_file_url',
        'main_category_id',
        'sub_category_id',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Main Category
     */
  public function mainCategory()
{
    return $this->belongsTo(
        VoCategory1::class,
        'main_category_id'
    );
}

public function subCategory()
{
    return $this->belongsTo(
        VoCategory2::class,
        'sub_category_id'
    );
}
}