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
}