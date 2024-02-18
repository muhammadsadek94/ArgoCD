<?php

namespace App\Domains\Favourite\Jobs\Api\V1\User;

use App\Domains\Favourite\Models\Favourite;
use INTCore\OneARTFoundation\Job;

class AddToFavouriteJob extends Job
{
    private $type;
    private $id;
    private $user_id;

    /**
     * Create a new job instance.
     *
     * @param string $id
     * @param string $type
     * @param string $user_id
     */
    public function __construct(string $id, string $type, string $user_id)
    {
        $this->type = $type;
        $this->id = $id;
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return Favourite
     */
    public function handle() : Favourite
    {
        return Favourite::firstOrCreate([
            'user_id' => $this->user_id,
            'favourable_id' => $this->id,
            'favourable_type' => $this->type
        ]);
    }
}