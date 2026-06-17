<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberWorkExperience extends Model
{
    use HasFactory;
    protected $primaryKey = 'experience_id';
    protected $fillable = ['member_id', 'company_name', 'position', 'start_date', 'end_date', 'responsibilities'];

    // ความสัมพันธ์: ประสบการณ์งานนี้เป็นของสมาชิกคนไหน
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
