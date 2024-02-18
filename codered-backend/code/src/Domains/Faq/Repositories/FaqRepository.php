<?php


namespace App\Domains\Faq\Repositories;


use App\Domains\Faq\Models\Faq;
use App\Domains\Faq\Enum\AppTypes;
use App\Foundation\Repositories\Repository;

class FaqRepository extends Repository implements FaqRepositoryInterface
{
    public function __construct(Faq $model) { parent::__construct($model); }

    /**
     * @param int $app_type
     * @return mixed
     */
    public function getFaqData($app_type = AppTypes::USER_APP)
    {
        $faqs = $this->model->active()
            ->where("app_type", $app_type)
            ->latest()
            ->get();

        return $faqs;
    }


}