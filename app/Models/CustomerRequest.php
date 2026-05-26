<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerRequest extends Model
{
    use HasFactory;
    protected $table = 'customer_request';
    protected $fillable = [
       'name',
       'email',
       'phone',
       'message',
       'type',
       'email_type',
    ];
}
