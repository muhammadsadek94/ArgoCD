<?php

namespace App\Domains\Enterprise\Features\Api\V1\SubAccount;

use App\Domains\Enterprise\Jobs\Api\V1\SubAccount\DeleteSubAccountLearnPathJob;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;


class DeleteSubAccountLearnPathFeature extends Feature
{

    /**
     * Create a new feature instance
     */
    public function __construct()
    {

    }


    public function handle(Request $request)
    {
        $this->run(DeleteSubAccountLearnPathJob::class, [
            'request' => $request
        ]);

        return $this->run(RespondWithJsonJob::class, [
            "content" => 'success'
        ]);
    }
}
