<?php

namespace App\Domains\Course\Jobs\Api\V1\User;

use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\LearnPathCertificate;
use App\Domains\Payments\Enum\LearnPathType;
use App\Domains\Payments\Models\LearnPathInfo;
use App\Domains\Uploads\Models\Upload;
use App\Domains\User\Models\User;
use Illuminate\Support\Facades\Storage;
use INTCore\OneARTFoundation\Job;
use Image;

class GenerateLearnPathCertificateJob extends Job
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
    public function __construct($user, LearnPathCertificate $result)
    {
        $this->user = $user;
        $this->result = $result;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): ?LearnPathCertificate
    {
        $learn_path = $this->result->learnPath;
        $path = null;
        $path = $this->generatePathCertificate($learn_path);

        if (is_null($path)) return null;

        $file = $this->saveToFileManagerDB($path);

        $this->result->update([
            'certificate_id' => $file->id
        ]);

        return $this->result->refresh();

    }

    function generatePathCertificate(LearnPathInfo $learn_path)
    {

        $name = ucwords(strtolower($this->user->full_name));
        $date = date('dS M Y');
        $number = $this->result->certificate_number;
        $course_name = $learn_path->name;

        $image = Image::make(resource_path('certificate-template/learnPath-certificate-ecl.png'));
        // set username
        $fontSize = 23;
        // set username
        $image->text($name, 421, 319.79, function ($font) use ($fontSize) {
            $font->color('#252533');
            $font->file(resource_path('certificate-template/Ubuntu-Bold.ttf'));
            $font->size($fontSize);
            $font->align('center');

        });

        // set date
        $image->text($date, 172, 540, function ($font) {
            $font->color('#252533');
            $font->file(resource_path('certificate-template/Ubuntu-Bold.ttf'));
            $font->size(16);
        });

        // set number
        $image->text($number, 600, 540, function ($font) {
            $font->color('#252533');
            $font->file(resource_path('certificate-template/Ubuntu-Bold.ttf'));
            $font->size(16);
            $font->align('center');

        });

        if (strlen($course_name) < 60) {
            $course_name_size = 23;
        } else {
            $course_name_size = 18;

        }
        $image->text($course_name, 421, 410, function ($font) use ($course_name_size) {
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
