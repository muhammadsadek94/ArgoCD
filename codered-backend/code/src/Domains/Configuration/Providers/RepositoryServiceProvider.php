<?php

namespace App\Domains\Configuration\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domains\Course\Repositories\CourseRepository;
use App\Domains\Course\Repositories\LessonRepository;
use App\Domains\Course\Repositories\ChaptersRepository;
use App\Domains\Course\Repositories\CourseTagsRepository;
use App\Domains\Course\Repositories\MicrodegreeRepository;
use App\Domains\Course\Repositories\CourseCategoryRepository;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Course\Repositories\LessonRepositoryInterface;
use App\Domains\Course\Repositories\ChaptersRepositoryInterface;
use App\Domains\Course\Repositories\CourseTagsRepositoryInterface;
use App\Domains\Course\Repositories\MicrodegreeRepositoryInterface;
use App\Domains\Course\Repositories\CourseCategoryRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //$this->app->bind(PackageSubscriptionRepositoryInterface::class, PackageSubscriptionRepository::class);
    }
}
