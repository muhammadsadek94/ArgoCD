<?php

namespace App\Domains\Course\Import;

use App\Domains\Course\Models\LessonVoucher;
use App\Domains\User\Models\User;
use Maatwebsite\Excel\Concerns\ToArray;
use INTCore\OneARTFoundation\Feature;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VoucherImport extends Feature implements ToArray, WithHeadingRow
{

    public $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @param array $row
     *
     * @return User|null
     */
    public function array(array $vouchers)
    {
        foreach ($vouchers as $voucher) {
            if(is_null($voucher['voucher']) || empty($voucher['voucher']) ) continue;

            $voucher_data = ['lesson_id' => $this->request->lesson_id, 'voucher' => $voucher['voucher']];
            LessonVoucher::create($voucher_data);
        }
    }


}
