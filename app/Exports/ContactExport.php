<?php

namespace App\Exports;

use App\Models\CustomerRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ContactExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return CustomerRequest::all([
            'name', 'phone', 'email', 'type', 'email_type', 'message', 'created_at'
        ]);
    }

    public function headings(): array
    {
        return [
            'ชื่อ', 'เบอร์ติดต่อ', 'อีเมล', 'แผนก', 'อีเมลแผนก', 'ข้อความ', 'วันที่สร้าง'
        ];
    }
}
