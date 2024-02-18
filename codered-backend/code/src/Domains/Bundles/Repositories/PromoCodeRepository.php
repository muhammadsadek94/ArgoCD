<?php

namespace App\Domains\Bundles\Repositories;

use App\Domains\Bundles\Models\PromoCode;
use App\Domains\Bundles\Repositories\Interfaces\PromoCodeRepositoryInterface;
use App\Foundation\Repositories\Repository;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;


class PromoCodeRepository extends Repository implements PromoCodeRepositoryInterface
{
    public function __construct(PromoCode $model)
    {
        parent::__construct($model);
    }


    /**
         * get promocode details 
         * @return mixed
    */

    public function getPromoCodeDetails()
    {
        $promo_code = $this->getModel()->active()->first();

        return $promo_code;

    }
 


   
    
     

}
