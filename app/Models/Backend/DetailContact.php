<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailContact extends Model
{
    protected $table = 'detail_contact';
    protected $fillable = [
        'order', 
        'name_th',
        'name_en',
        'address_th',
        'address_en',
        'email',
        'phone',
    ];

    function detail(){
        return $this->belongsTo(Contact::class);
    }

}
