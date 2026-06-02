<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    use HasFactory;

    protected $table = 'job_categories';

    protected $fillable = [

        'job_id',
        'category1_id',
        'category2_id',

    ];

    public function job()
    {
        return $this->belongsTo(
            Job::class,
            'job_id'
        );
    }
   

    public function category1()
    {
        return $this->belongsTo(
            Category1::class,
            'category1_id'
        );
    }

    public function category2()
    {
        return $this->belongsTo(
            Category2::class,
            'category2_id'
        );
    }
}