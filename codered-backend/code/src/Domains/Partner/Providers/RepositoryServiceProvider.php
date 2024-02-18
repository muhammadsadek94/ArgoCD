<?php

namespace App\Domains\Partner\Providers;

use App\Domains\Partner\Repositories\CourseCategoryRepository;
use App\Domains\Partner\Repositories\CourseRepository;
use App\Domains\Partner\Repositories\Interfaces\CourseCategoryRepositoryInterface;
use App\Domains\Partner\Repositories\Interfaces\CourseRepositoryInterface;
use App\Domains\Partner\Repositories\Interfaces\LessonRepositoryInterface;
use App\Domains\Partner\Repositories\LessonRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(CourseCategoryRepositoryInterface::class, CourseCategoryRepository::class);
        $this->app->bind(LessonRepositoryInterface::class, LessonRepository::class);
    }
}
