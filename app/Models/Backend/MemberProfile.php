<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class MemberProfile extends Model
{
    protected $table = 'member_profiles';

    protected $guarded = [];

    public function member()
    {
        return $this->belongsTo(
            Member::class,
            'member_id'
        );
    }
}
