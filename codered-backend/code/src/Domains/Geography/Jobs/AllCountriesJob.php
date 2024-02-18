<?php

namespace App\Domains\Geography\Jobs;

use App\Domains\Geography\Models\Country;
use App\Foundation\Repositories\Repository;
use INTCore\OneARTFoundation\Job;

class AllCountriesJob extends Job
{
    protected $request;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $countries = Country::with([
            "cities" => function($query) {
                $query->active();
            }]
        )->active()->country()->orderBy('name_en', 'asc')->get();
        return $countries;
    }
}
