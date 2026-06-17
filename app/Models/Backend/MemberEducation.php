<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberEducation extends Model
{
    protected $table = 'member_educations';

    protected $guarded = [];

    protected $fillable = [
        'member_id',
        'education_level',
        'education_type',
        'institution_name',
        'faculty',
        'major',
        'gpa',
        'start_month',
        'start_year',
        'end_month',
        'end_year',
        'is_current',
        'study_status',
        'note',
    ];

    protected $casts = [
        'gpa' => 'decimal:2',
        'is_current' => 'boolean',
    ];
    public function member()
    {
        return $this->belongsTo(
            Member::class,
            'member_id'
        );
    }
}
