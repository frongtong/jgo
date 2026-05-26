<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerSub extends Model
{
    use HasFactory;
    protected $table = 'bannersub';
    protected $fillable = [
        'name',
        'image',
        'sort',
    ];
    public function getImageAttribute($value)
{
    // เปลี่ยน image ให้เป็น full URL
    return $value ? asset($value) : null;
}
}
