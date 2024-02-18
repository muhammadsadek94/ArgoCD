<?php

namespace App\Domains\User\Jobs\Admin\Instructor;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use INTCore\OneARTFoundation\Job;
use PDF;

class PayoutPDFExportJob extends Job implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;
    /**
     * @var array
     */
    private $request;

    private $timeout = 3600;
    private $admin;

    /**
     * Create a new job instance.
     *
     * @param array $request
     * @param $admin
     */
    public function __construct(array $request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return bool
     */
    public function handle()
    {

       
    }

    private function getQuarter(): array
    {
        $quarters = [
            1 => [
                'start_date' => '{{year}}-01-01',
                'end_date' => '{{year}}-03-31',
            ],
            2 => [
                'start_date' => '{{year}}-04-01',
                'end_date' => '{{year}}-06-30',
            ],
            3 => [
                'start_date' => '{{year}}-07-01',
                'end_date' => '{{year}}-09-30',
            ],
            4 => [
                'start_date' => '{{year}}-10-01',
                'end_date' => '{{year}}-12-31',
            ]
        ];

        $selected_quarter = $quarters[$this->request['quarter']];

        $start_date = $selected_quarter['start_date'];
        $end_date = $selected_quarter['end_date'];

        $start_date = str_replace('{{year}}', $this->request['year'], $start_date);
        $end_date = str_replace('{{year}}', $this->request['year'], $end_date);

        return [
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];
    }

}
