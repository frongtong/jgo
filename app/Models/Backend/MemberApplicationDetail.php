<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class MemberApplicationDetail extends Model
{
    protected $table = 'member_application_details';

    protected $guarded = [];

    protected $casts = [
        'personal' => 'array',
        'education_extra' => 'array',
        'language_training' => 'array',
        'work_family' => 'array',
        'health' => 'array',
        'additional' => 'array',
        'responsibility' => 'array',
        'guarantor' => 'array',
        'goals' => 'array',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
