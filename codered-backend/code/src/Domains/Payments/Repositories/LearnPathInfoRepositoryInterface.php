<?php

namespace App\Domains\Payments\Repositories;

use App\Foundation\Repositories\RepositoryInterface;
use Request;

interface LearnPathInfoRepositoryInterface extends  RepositoryInterface
{
    public function filtration($request, $user_id = null);
    public function getLearnPathsOnly();
    public function getBundles($request);
    public function createCertificateFile($user_id, $learn_path_id, int $percentage  , int $certificate_number = 0);
    public function getPathWithFilters($request,$type, $user_id = null);

}
