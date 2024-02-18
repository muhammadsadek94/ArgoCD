<?php

namespace App\Domains\User\Export;

use App\Domains\User\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReceiptExport implements FromCollection, WithHeadings, WithMapping
{

    private $payouts;

    public function collection()
    {
        return $this->payouts;
    }

    public function __construct($payoutArray)
    {
        $this->payouts = $payoutArray;
    }

    public function headings(): array
    {


        return [
            'Instructor Name',
            'Course Name',
            'year',
            'quarter',

            'Mins watched from Pro subscriptions (Course)',
            'Mins watched from Pro subscriptions (Platform)',

            'Mins watched from Active users (Course)',
            'Mins watched from Active users (Platform)',

            'Billable Revenue',
            'Revenue Contribution % (Pro only)',
            'Revenue Contribution (USD)',

            'Course Commission',
            'Royalties',
        ];


    }

    public function map($row): array
    {
        return $row;
    }
}
