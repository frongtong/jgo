<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class AlumniBanner extends Model
{
    protected $table = 'alumni_banners';

    protected $guarded = [];

    public function alumni()
    {
        return $this->belongsTo(Alumni::class, 'alumni_id');
    }
}
