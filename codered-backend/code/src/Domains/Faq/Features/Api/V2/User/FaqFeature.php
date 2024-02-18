<?php

namespace App\Domains\Faq\Features\Api\V2\User;

use App\Domains\Faq\Http\Resources\Api\V2\FaqResource;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Faq\Repositories\FaqRepositoryInterface;

class FaqFeature extends Feature
{
    /**
     * @param Request $request
     * @param FaqRepositoryInterface $faq_repository
     * @return mixed
     */
    public function handle(Request $request, FaqRepositoryInterface $faq_repository)
    {
        $faqs = $faq_repository->getFaqData();

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                "faq" => FaqResource::collection($faqs)
            ]
        ]);
    }
}
