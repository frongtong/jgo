<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyModel extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $fillable = [

        'name_th',
        'name_en',
        'name_jp',

        'logo',
        'cover_image',

        'description',

        'website',

        'address',

        'country_id',
        'province_id',
        'city_id'

    ];


    /*
    |--------------------------------------------------------------------------
    | LOCATION
    |--------------------------------------------------------------------------
    */

    public function country()
    {
        return $this->belongsTo(
            Location::class,
            'country_id'
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


    /*
    |--------------------------------------------------------------------------
    | JOBS
    |--------------------------------------------------------------------------
    */

    public function jobs()
    {
        return $this->hasMany(
            JobModel::class,
            'company_id'
        );
    }
}