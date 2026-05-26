<?php

namespace App\Models\Authuse;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MemberAuth extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'members';

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}