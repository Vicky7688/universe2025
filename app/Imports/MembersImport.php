<?php
namespace App\Imports;

use App\Models\AccountOpening;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MembersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
 
        return new AccountOpening([
            'openingDate'           =>  '2024-09-02',
            'customer_Id'           =>  $row['accountid'],
            'name'                  =>  $row['account_name'],
            'openingbal'            =>  $row['op_bal'],// Column index for 'father_husband'
            'address'                => $row['address_line_1'],
            'landmark'             =>  $row['address_line_2'],
            'city'             =>  $row['city'], // Column index for 'adhaar_no'
            'state'             => $row['state'], // Column index for 'adhaar_no'
            'mobile_first'             =>  $row['mobile'], // Column index for 'adhaar_no'
            'loan_limit'             =>  $row['credit_limit'], // Column index for 'adhaar_no'
        ]);
    }
    public function headingRow(): int
    {
        return 1; // Adjust if the headings row is not the first row
    }
}
