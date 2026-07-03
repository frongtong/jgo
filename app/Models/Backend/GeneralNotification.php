<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class GeneralNotification extends Model
{
    protected $table = 'general_notifications';

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
