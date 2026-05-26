<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailCustomer extends Model
{
    use HasFactory;
    protected $table = 'email_customer';
    protected $fillable = [ 
        'PrefixThai',
        'FirstNameThai',
        'LastNameThai',
        'Email',
    ];
}
