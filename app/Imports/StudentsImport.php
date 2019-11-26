<?php

namespace App\Imports;

use App\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

class StudentsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    use Importable;
    public function model(array $row)
    {       
            if (!isset($row[0])) {
                return null;
            }

            return new Student([
            'career' => $row[0], 
            'id_user' => $row[1], 
            'period' => $row[2], 
            'paternal_surname' => $row[3], 
            'maternal_surname' => $row[4], 
            'name' => $row[5], 
            'id_group' => $row[8], 
            // 'condition' => $row[],
            'grade' => $row[9], 
        
        ]);
    }
}

