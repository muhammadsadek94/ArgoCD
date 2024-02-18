<?php


namespace App\Domains\Voucher\Exports;


use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VouchersExport implements \Maatwebsite\Excel\Concerns\FromArray, WithMapping, WithHeadings
{
    /**
     * @var array
     */
    private $vouchers;

    /**
     * VouchersExport constructor.
     * @param array $vouchers
     */
    public function __construct(array $vouchers)
    {

        $this->vouchers = $vouchers;
    }

    /**
     * @inheritDoc
     */
    public function array(): array
    {
        return $this->vouchers;
    }

    public function headings(): array
    {
        return [
            'Name', 'Voucher', 'Expired At', 'Package'
        ];
    }

    public function map($row): array
    {
        return [
            $row->name,
            $row->voucher,
            $row->expired_at,
            $row->payable->name ?? ''
        ];
    }
}
