<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $table = 'job_applications';

    protected $guarded = [];

    public $timestamps = true;

    /*
    |--------------------------------------------------------------------------
    | Member
    |--------------------------------------------------------------------------
    */

    public function member()
    {
        return $this->belongsTo(
            Member::class,
            'member_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Job
    |--------------------------------------------------------------------------
    */

    public function job()
    {
        return $this->belongsTo(
            Job::class,
            'job_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Province
    |--------------------------------------------------------------------------
    */

    public function province()
    {
        return $this->belongsTo(
            Location::class,
            'province_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | City
    |--------------------------------------------------------------------------
    */

    public function city()
    {
        return $this->belongsTo(
            Location::class,
            'city_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Logs
    |--------------------------------------------------------------------------
    */

    public function logs()
    {
        return $this->hasMany(
            JobApplicationLog::class,
            'application_id'
        );
    }
}