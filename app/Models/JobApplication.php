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

    ];

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