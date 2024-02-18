<?php

namespace App\Domains\Geography\Jobs\Api;

use App\Domains\Geography\Models\City;
use INTCore\OneARTFoundation\Job;

class GetCountryCitiesJob extends Job
{
    public $request;
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
        $country_cities = City::where("activation", "1")->where("country_id", $this->request->country_id)->pluck("name_en", "id");
        return $country_cities;
    }
}
