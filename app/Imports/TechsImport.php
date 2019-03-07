<?php

namespace App\Imports;

use App\Models\Technician;
use Maatwebsite\Excel\Concerns\ToModel;

class TechsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Technician([
            //
        ]);
    }
}
