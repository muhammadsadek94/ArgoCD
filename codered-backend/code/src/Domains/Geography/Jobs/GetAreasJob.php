<?php

namespace App\Domains\Geography\Jobs;

use App\Domains\Geography\Models\Area;
use App\Domains\Geography\Models\Country;
use App\Foundation\Repositories\Repository;
use INTCore\OneARTFoundation\Job;

class GetAreasJob extends Job
{
    private $limit;
    private $isPaginated;

    /**
     * GetAreasJob constructor.
     * @param int $limit
     * @param bool $isPaginated
     */
    public function __construct($limit = 5, $isPaginated = false)
    {

        $this->limit = $limit;
        $this->isPaginated = $isPaginated;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $areas = Area::active();

        if($this->isPaginated) {
            $areas = $areas->paginate($this->limit);
        } else {
            $areas = $areas->limit($this->limit)->get();
        }

        return $areas;
    }

}
