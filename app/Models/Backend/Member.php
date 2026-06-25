<?php

namespace App\Models\Backend;


use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'members';

    protected $guarded = [];

    protected $hidden = [
        'password',
        'parent_plain_password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    public function profile()
    {
        return $this->hasOne(
            MemberProfile::class,
            'member_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Education
    |--------------------------------------------------------------------------
    */

    public function educations()
    {
        return $this->hasMany(
            MemberEducation::class,
            'member_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Parent
    |--------------------------------------------------------------------------
    */

    public function parent()
    {
        return $this->belongsTo(
            Member::class,
            'parent_id'
        );
    }

    public function parents()
    {
        return $this->belongsToMany(
            Member::class,
            'member_parent',
            'member_id',
            'parent_id'
        )->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Children
    |--------------------------------------------------------------------------
    */

    public function children()
    {
        return $this->hasMany(
            Member::class,
            'parent_id'
        );
    }

    public function linkedChildren()
    {
        return $this->belongsToMany(
            Member::class,
            'member_parent',
            'parent_id',
            'member_id'
        )->withTimestamps();
    }
    public function workExperiences()
    {
        return $this->hasMany(MemberWorkExperience::class, 'member_id', 'id');
    }

    public function trainingCourses()
    {
        return $this->hasMany(MemberTrainingCourse::class, 'member_id', 'id');
    }

    public function applicationDetail()
    {
        return $this->hasOne(MemberApplicationDetail::class, 'member_id', 'id');
    }
 
}
