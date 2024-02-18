<?php

namespace App\Domains\Course\Providers;

use App\Domains\Course\Repositories\AssessmentsAnswerRepository;
use App\Domains\Course\Repositories\AssessmentsAnswerRepositoryInterface;
use App\Domains\Course\Repositories\AssessmentsRepository;
use App\Domains\Course\Repositories\AssessmentsRepositoryInterface;
use App\Domains\Course\Repositories\CertificationRepository;
use App\Domains\Course\Repositories\CertificationRepositoryInterface;
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
use App\Domains\Course\Repositories\CourseLevelRepository;
use App\Domains\Course\Repositories\JobRoleRepositoryInterface;
use Laravel\Horizon\Contracts\JobRepository;
use App\Domains\Course\Repositories\JobRoleRepository;
use App\Domains\Course\Repositories\SpecialtyAreaRepository;
use App\Domains\Course\Repositories\SpecialtyAreaRepositoryInterface;
use App\Domains\Partner\Repositories\Interfaces\CourseLevelRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CourseTagsRepositoryInterface::class, CourseTagsRepository::class);
        $this->app->bind(CourseCategoryRepositoryInterface::class, CourseCategoryRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(LessonRepositoryInterface::class, LessonRepository::class);
        $this->app->bind(ChaptersRepositoryInterface::class, ChaptersRepository::class);
        $this->app->bind(MicrodegreeRepositoryInterface::class, MicrodegreeRepository::class);
        $this->app->bind(CertificationRepositoryInterface::class, CertificationRepository::class);
        $this->app->bind(AssessmentsRepositoryInterface::class, AssessmentsRepository::class);
        $this->app->bind(AssessmentsAnswerRepositoryInterface::class, AssessmentsAnswerRepository::class);
        $this->app->bind(JobRoleRepositoryInterface::class, JobRoleRepository::class);
        $this->app->bind(SpecialtyAreaRepositoryInterface::class, SpecialtyAreaRepository::class);
    }
}
