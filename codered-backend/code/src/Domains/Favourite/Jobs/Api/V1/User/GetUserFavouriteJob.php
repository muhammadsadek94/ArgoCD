<?php

namespace App\Domains\Favourite\Jobs\Api\V1\User;

use App\Domains\Property\Models\Property;
use App\Domains\Favourite\Enum\FavouriteType;
use App\Domains\Favourite\Models\Favourite;
use INTCore\OneARTFoundation\Job;

class GetUserFavouriteJob extends Job
{
    private $user_id;
    private $type;
    private $perPage;

    /**
     * Create a new job instance.
     *
     * @param string $user_id
     * @param string $type
     * @param int $perPage
     */
    public function __construct(string $user_id, string $type, $perPage = 10)
    {
        $this->user_id = $user_id;
        $this->type = $type;
        $this->perPage = $perPage;
    }

    /**
     * Execute the job.
     *
     * @return Favourite
     */
    public function handle()
    {
        $morphs = FavouriteType::getMorphs();
        $model =  new $morphs[$this->type];

        $favourable_ids = Favourite::where([
            'favourable_type' => $this->type,
            'user_id'=> $this->user_id
        ])->pluck('favourable_id')->toArray();



        return $model->whereIn('id', $favourable_ids)->paginate($this->perPage);
    }
}
