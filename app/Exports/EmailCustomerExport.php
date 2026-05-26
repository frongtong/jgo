<?php

namespace App\Exports;


use App\Models\Backend\Email_customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class EmailCustomerExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
     public function collection()
    {
        return Email_customer::select('id', 'email', 'created_at')->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'อีเมล',
            'วันที่สร้าง',
        ];
    }
}
