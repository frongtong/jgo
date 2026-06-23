<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    protected $table = 'alumni';

    protected $guarded = [];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function banners()
    {
        return $this->hasMany(AlumniBanner::class, 'alumni_id')->orderBy('sort_order');
    }
}
