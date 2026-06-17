<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberTrainingCourse extends Model
{
    protected $table = 'member_training_courses';
    protected $primaryKey = 'training_id';
    protected $fillable = ['member_id', 'program_type', 'institution_name', 'start_month_year', 'end_month_year'];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
