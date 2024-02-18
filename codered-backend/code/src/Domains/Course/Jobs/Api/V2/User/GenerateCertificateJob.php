<?php

namespace App\Domains\Course\Jobs\Api\V1\User;

use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\Course;
use App\Domains\Payments\Enum\LearnPathType;
use App\Domains\Uploads\Models\Upload;
use App\Domains\User\Models\User;
use Illuminate\Support\Facades\Storage;
use INTCore\OneARTFoundation\Job;
use Image;

class GenerateCertificateJob extends Job
{
    /**
     * @var User
     */
    private $user;
    private $result;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param $result
     */
    public function __construct(User $user, CompletedCourses $result)
    {
        $this->user = $user;
        $this->result = $result;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): ?CompletedCourses
    {
        $course = $this->result->course;
        $path = null;
        if ($course->course_type == CourseType::COURSE) {
            $path = $this->generateCourseCertificate($course);
        }
        if ($course->course_type == CourseType::MICRODEGREE ||
            $course->course_type == CourseType::COURSE_CERTIFICATION) {
            $path = $this->generateMicroDegreeCertificate($course);
        }
        if ($course->course_type == CourseType::COURSE_CERTIFICATION) {
            $path = $this->generateMicroDegreeCertificate($course);
        }

        if (is_null($path)) return null;

        $file = $this->saveToFileManagerDB($path);

        $this->result->update([
            'certificate_id' => $file->id
        ]);

        return $this->result->refresh();
    }

    private function generateMicroDegreeCertificate(Course $course)
    {
        $name = ucwords(strtolower($this->user->full_name));
        $date = date('dS M Y');
        $number = $this->result->certificate_number;
        $course_name = $course->name;

        $image = Image::make(resource_path('certificate-template/microdegree-certificate.png'));

        // set username
        if (strlen($name) < 26) {
            $fontSize = 90.01;
        } else {
            $fontSize = 57.01;
        }
        $image->text($name, 650, 620.79, function ($font) use ($fontSize) {
            $font->color('#FE000D');

            $font->file(resource_path('certificate-template/GreatVibes-Regular.otf'));
            $font->size($fontSize);
        });

        // set date
        $image->text($date, 640, 1000, function ($font) {
            $font->color('#FFFFFF');
            $font->file(resource_path('certificate-template/Ubuntu-Bold.ttf'));
            $font->size(45);
        });

        // set number
        $image->text($number, 230, 1000, function ($font) {
            $font->color('#FFFFFF');
            $font->file(resource_path('certificate-template/Ubuntu-Bold.ttf'));
            $font->size(45);
        });

        // set course name
        if (strlen($course_name) < 82) {
            $fontSize = 35;
        } else {
            $fontSize = 28;
        }
        $image->text($course_name, 780, 810, function ($font) use ($fontSize) {
            $font->color('#FFFFFF');
            $font->file(resource_path('certificate-template/Ubuntu-Bold.ttf'));
            $font->size($fontSize);
            $font->align('center');
        });
        $path = "/certificates/{$this->result->id}.png";
        $certificate_png = $image->stream('png', 100);
        Storage::put($path, $certificate_png->__toString());

        return $path;
    }

    private function generateCourseCertificate(Course $course)
    {

        $name = ucwords(strtolower($this->user->full_name));
        $date = date('dS M Y');
        $number = $this->result->certificate_number;
        $course_name = $course->name;

        $image = Image::make(resource_path('certificate-template/pro-course-certificate-ecl.png'));

        // set username
        if (strlen($name) < 39) {
            $fontSize = 95.01;
        } else {
            $fontSize = 60.01;
        }
        // set username
        $image->text($name, 950, 640.79, function ($font) use ($fontSize) {
            $font->color('#E02522');
            $font->file(resource_path('certificate-template/GreatVibes-Regular.otf'));
            $font->size($fontSize);
            $font->align('center');
        });

        // set date
        $image->text($date, 1200, 970, function ($font) {
            $font->color('#323133');
            $font->file(resource_path('certificate-template/Ubuntu-Bold.ttf'));
            $font->size(45);
        });

        // set number
        $image->text($number, 450, 990, function ($font) {
            $font->color('#323133');
            $font->file(resource_path('certificate-template/Ubuntu-Bold.ttf'));
            $font->size(45);
        });

        if (strlen($course_name) < 70) {
            $course_name_size =  35;
        } else {
            $course_name_size =  27;
        }
        $image->text($course_name, 800, 800, function ($font) use ($course_name_size) {
            $font->color('#323133');
            $font->file(resource_path('certificate-template/Ubuntu-Bold.ttf'));
            $font->size($course_name_size);
            $font->align('center');
        });
        $path = "/certificates/{$this->result->id}.png";
        $certificate_png = $image->stream('png', 100);
        Storage::put($path, $certificate_png->__toString());

        return $path;
    }


    private function generatePathCertificate(Course $course, string $learnPathType)
    {

        $name = ucwords(strtolower($this->user->full_name));
        $date = date('dS M Y');
        $number = $this->result->certificate_number;
        $course_name = $course->name;
        if ($learnPathType == LearnPathType::CAREER)
            $certificate_path = 'career';
        if ($learnPathType == LearnPathType::SKILL)
            $certificate_path = 'skill';
        if ($learnPathType == LearnPathType::CERTIFICATE)
            $certificate_path = 'certificate';

        $image = Image::make(resource_path('certificate-template/' . $certificate_path . '-path.png'));
        // set username
        $fontSize = 23;
        // set username
        $image->text($name, 421, 300.79, function ($font) use ($fontSize) {
            $font->color('#252533');
            $font->file(resource_path('certificate-template/Ubuntu-Bold.ttf'));
            $font->size($fontSize);
            $font->align('center');
        });

        // set date
        $image->text($date, 172, 510, function ($font) {
            $font->color('#252533');
            $font->file(resource_path('certificate-template/Ubuntu-Bold.ttf'));
            $font->size(16);
        });

        // set number
        $image->text($number, 600, 510, function ($font) {
            $font->color('#252533');
            $font->file(resource_path('certificate-template/Ubuntu-Bold.ttf'));
            $font->size(16);
        });

        if (strlen($course_name) < 60) {
            $course_name_size = 23;
        } else {
            $course_name_size = 18;
        }
        $image->text($course_name, 421, 415, function ($font) use ($course_name_size) {
            $font->color('#252533');
            $font->file(resource_path('certificate-template/Ubuntu-Bold.ttf'));
            $font->size($course_name_size);
            $font->align('center');
        });
        $path = "/certificates/{$this->result->id}.png";
        $certificate_png = $image->stream('png', 100);
        Storage::put($path, $certificate_png->__toString());

        return $path;
    }

    private function saveToFileManagerDB(string $path): Upload
    {
        return Upload::create([
            "path" => $path,
            "full_url" => Storage::url($path),
            "size" => 'system-generated',
            "mime_type" => 'image/png',
            'in_use' => 1,
            'container' => config('filesystems.disks.azure.container')
        ]);
    }
}
