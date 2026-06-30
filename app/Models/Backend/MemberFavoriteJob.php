<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class MemberFavoriteJob extends Model
{
    protected $table = 'member_favorite_jobs';

    protected $guarded = [];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
}
