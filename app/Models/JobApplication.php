<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $table = 'job_applications';

    protected $fillable = [

        'member_id',
        'job_id',
        'first_name',
        'last_name',
        'gender',
        'age',

        'phone',
        'email',

        'line_id',

        'province_id',
        'city_id',

        'address',

        'education',

        'work_experience',

        'japanese_level',

        'resume_file',

        'note',

        'status',
        'interview_date',
        'interview_time',
        'interview_location',
        'hr_note',

    ];

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
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function job()
    {
        return $this->belongsTo(
            Job::class,
            'job_id'
        );
    }

    public function province()
    {
        return $this->belongsTo(
            Location::class,
            'province_id'
        );
    }

    public function city()
    {
        return $this->belongsTo(
            Location::class,
            'city_id'
        );
    }
}
