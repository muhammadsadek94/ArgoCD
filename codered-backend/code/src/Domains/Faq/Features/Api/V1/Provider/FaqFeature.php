<?php

namespace App\Domains\Faq\Features\Api\V1\Provider;

use Illuminate\Http\Request;
use App\Domains\Faq\Enum\AppTypes;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Faq\Jobs\Api\V1\Provider\GetAllFaqsJob;
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
        $faqs = $faq_repository->getFaqData(AppTypes::PROVIDER_APP);

        return $this->run(RespondWithJsonJob::class, [
            "content" => ["faq" => $faqs]
        ]);
    }
}
