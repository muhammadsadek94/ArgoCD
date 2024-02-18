<?php

namespace App\Domains\Enterprise\Imports;

use App\Domains\User\Models\User;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImportValidation implements ToArray, WithHeadingRow
{

    /**
     * @param array $row
     *
     * @return User|null
     */
    public function array(array $users)
    {
        return $users;       
    }

}