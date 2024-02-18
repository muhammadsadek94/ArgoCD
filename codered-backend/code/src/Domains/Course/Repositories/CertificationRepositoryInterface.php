<?php

namespace App\Domains\Course\Repositories;

use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Collection;
use App\Foundation\Repositories\RepositoryInterface;

interface CertificationRepositoryInterface extends RepositoryInterface
{
    public function getCertifications(int $limit = 4, array $with = []) :Collection;

    public function getNotEnrolledCertifications(User $user) :Collection;

    public function getCompletedCertifications(User $user) :Collection;
    
    public function getNotCompletedCertifications(User $user) :Collection;
}