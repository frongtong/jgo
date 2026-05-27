<?php
namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
       protected $table = 'locations';
    protected $fillable = ['name', 'parent_id'];

    
    public function children()
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

  
    public function parent()
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }
}