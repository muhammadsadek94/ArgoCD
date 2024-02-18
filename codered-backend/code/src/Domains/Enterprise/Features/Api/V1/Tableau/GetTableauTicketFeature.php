<?php

namespace App\Domains\Enterprise\Features\Api\V1\Tableau;

use App\Domains\Enterprise\Services\Tableau\TableauService;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;


class GetTableauTicketFeature extends Feature
{

    /**
     * Create a new feature instance
     */
    public function __construct()
    {

    }


    public function handle(Request $request)
    {
        $tableau_services = new TableauService();
        $ticket = $tableau_services->CreateTicket();

        if ($ticket == -1) {
            return $this->run(RespondWithJsonErrorJob::class, [
                "errors" => [
                    'name' => 'tableau',
                    "message" => 'Report request cannot be resolved'
                ]
            ]);
        }

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'Tableau' => [
                    'ticket' => $ticket,
                ]
            ]
        ]);
    }
}
