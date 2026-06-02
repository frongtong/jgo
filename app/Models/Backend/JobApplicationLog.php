<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplicationLog extends Model
{
    use HasFactory;

    protected $table = 'job_application_logs';

    protected $fillable = [

        'application_id',

        'old_status',
        'new_status',

        'remark',

        'created_by',

    ];

    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function application()
    {
        return $this->belongsTo(
            JobApplication::class,
            'application_id'
        );
    }
}