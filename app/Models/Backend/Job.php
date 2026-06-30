<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $table = 'jobs';

    protected $fillable = [

        'company_id',

        'title_th',
        'title_en',
        'title_jp',

        'slug',

        'logo',
        'banner_image',

        'job_type',

        'salary_type',

        'salary_min',
        'salary_max',

        'currency',

        'gender',

        'age_min',
        'age_max',

        'qty',

        'province_id',
        'city_id',
        'date',

        'work_time',
        'overtime',

        'holiday',

        'start_work_date',

        'detail',
        'welfare',

        'map_link',

        'view_count',

        'status',

    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function company()
    {
        return $this->belongsTo(
            CompanyModel::class,
            'company_id'
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

    public function categories()
    {
        return $this->hasMany(
            JobCategory::class,
            'job_id'
        );
    }

    public function images()
    {
        return $this->hasMany(
            JobImage::class,
            'job_id'
        );
    }

    public function applications()
    {
        return $this->hasMany(
            JobApplication::class,
            'job_id'
        );
    }

    public function favoriteMembers()
    {
        return $this->belongsToMany(
            Member::class,
            'member_favorite_jobs',
            'job_id',
            'member_id'
        )->withTimestamps();
    }
}
