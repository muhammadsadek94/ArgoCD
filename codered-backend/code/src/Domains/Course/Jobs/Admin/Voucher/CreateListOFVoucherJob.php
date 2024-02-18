<?php

namespace App\Domains\Course\Jobs\Admin\Voucher;

use App\Domains\Course\Models\LessonVoucher;
use INTCore\OneARTFoundation\Job;
use phpDocumentor\Reflection\Types\This;

/**
 * @deprecated
 */
class CreateListOFVoucherJob extends Job
{
    private $vouhcers;
    private $lesson_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($vouhcers, $lesson_id)
    {
        $this->vouhcers = $vouhcers;
        $this->lesson_id = $lesson_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->vouhcers = collect($this->vouhcers)->map(function ($vouchers) {
            $data = [];
            foreach ($vouchers as $index => $voucher) {
                $row = ['lesson_id' => $this->lesson_id, 'voucher' => $voucher['voucher']];
                $data[$index] = $row;
            }
            return $data;
        })->toArray();
        foreach ($this->vouhcers[0] as $vouhcer) {
            LessonVoucher::create($vouhcer);
        }
    }
}
