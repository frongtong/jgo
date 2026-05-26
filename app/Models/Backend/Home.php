<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Home extends Model
{
    use HasFactory;
    protected $table = 'home';
    protected $fillable = [    
        'link',
        'img_bg',

    ];
    public function getImgBgAttribute($value)
    {
        // เปลี่ยน img_bg ให้เป็น full URL
        return $value ? asset($value) : null;
    }
}
