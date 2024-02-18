<?php

namespace App\Domains\Geography\Jobs\Api;

use App\Domains\Geography\Models\Area;
use INTCore\OneARTFoundation\Job;

class GetCitiesAreasJob extends Job
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
        $areas = Area::where("activation", "1")->where("city_id", $this->request->city_id)->pluck("name_en", "id");
        return $areas;
    }
}
