<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $table = 'job_applications';

    protected $guarded = [];

    public $timestamps = true;

    public const STATUS_NEW = 'new';
    public const STATUS_REVIEWING = 'reviewing';
    public const STATUS_INTERVIEW = 'interview';
    public const STATUS_PASSED = 'passed';
    public const STATUS_FAILED = 'failed';

    protected $casts = [
        'interview_date' => 'date',
    ];

    public static function activeStatuses(): array
    {
        return [
            self::STATUS_NEW,
            self::STATUS_REVIEWING,
            self::STATUS_INTERVIEW,
        ];
    }

    public static function allStatuses(): array
    {
        return [
            self::STATUS_NEW,
            self::STATUS_REVIEWING,
            self::STATUS_INTERVIEW,
            self::STATUS_PASSED,
            self::STATUS_FAILED,
        ];
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_NEW => 'สมัครใหม่',
            self::STATUS_REVIEWING => 'รอตรวจสอบ',
            self::STATUS_INTERVIEW => 'นัดสัมภาษณ์',
            self::STATUS_PASSED => 'ผ่าน',
            self::STATUS_FAILED => 'ไม่ผ่าน',
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
