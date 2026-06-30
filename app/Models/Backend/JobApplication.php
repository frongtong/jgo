<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $table = 'job_applications';

    protected $guarded = [];

    public $timestamps = true;

    public const STATUS_PENDING = 'pending';
    public const STATUS_INTERVIEW = 'interview';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_COMPLETED = 'completed';

    public static function activeStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_INTERVIEW,
            self::STATUS_APPROVED,
        ];
    }

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
