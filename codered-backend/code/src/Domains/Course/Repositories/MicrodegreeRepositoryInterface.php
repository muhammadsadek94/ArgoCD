<?php

namespace App\Domains\Course\Repositories;

use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Collection;
use App\Foundation\Repositories\RepositoryInterface;

interface MicrodegreeRepositoryInterface extends RepositoryInterface
{
    public function getMicrodegrees(int $limit = 4, array $with = []) :Collection;

    public function getMicroDegreeById(string $id);

    public function getNotEnrolledMicrodegrees(User $user) :Collection;

    public function getCompletedMicrodegrees(User $user) :Collection;
    
    public function getNotCompletedMicrodegrees(User $user) :Collection;
}