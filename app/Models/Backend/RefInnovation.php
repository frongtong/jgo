<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefInnovation extends Model
{
    use HasFactory;
    protected $table = 'ref_innovation';
    protected $fillable = [
        'id_innovation',
        'url',
        'text_ref', 
        'date',
    ];
}
