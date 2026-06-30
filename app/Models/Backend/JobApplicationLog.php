<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class JobApplicationLog extends Model
{
    protected $table = 'job_application_logs';

    protected $guarded = [];

    public $timestamps = true;

    const UPDATED_AT = null;

    /*
    |--------------------------------------------------------------------------
    | Application
    |--------------------------------------------------------------------------
    */

    public function application()
    {
        return $this->belongsTo(
            JobApplication::class,
            'application_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Created By
    |--------------------------------------------------------------------------
    */

    public function creator()
    {
        return $this->belongsTo(
            \App\Models\Authuse\Admin::class,
            'created_by'
        );
    }
}
