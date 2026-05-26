<?php

namespace App\Imports;

use App\Models\EmailCustomer;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ImportsEmailCustomer implements ToModel, WithHeadingRow
{
    use Importable;

    /**
     * Define how each row should be transformed into a model instance.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (!empty($row['email'])) {
            return new EmailCustomer([
                'PrefixThai' => $row['prefixthai'] ?? null,
                'FirstNameThai' => $row['firstnamethai'] ?? null,
                'LastNameThai' => $row['lastnamethai'] ?? null,
                'Email' => $row['email'] ?? null,
            ]);
        }
        return null;
    }
}
