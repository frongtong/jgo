<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberEducation extends Model
{
    protected $table = 'member_educations';

    protected $guarded = [];

    public function member()
    {
        return $this->belongsTo(
            Member::class,
            'member_id'
        );
    }
}
