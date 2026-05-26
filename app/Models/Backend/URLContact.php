<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class URLContact extends Model
{
    protected $table = 'url_contact';
    protected $fillable = [
        'type',
        'url',
        'active',
    ];

}
