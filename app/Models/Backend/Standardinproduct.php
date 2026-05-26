<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Standardinproduct extends Model
{
    use HasFactory;
    protected $table = 'standard_inproduct';
    protected $fillable = [
        'product_id',
        'standardproduct_id',
    ];

    public function standardproduct(){
        return $this->belongsTo(Standardproduct::class, 'standardproduct_id', 'id');
    }
}
