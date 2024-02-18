<?php

namespace App\Domains\Course\Jobs\Api\V1\User;

use App\Domains\Course\Models\Lesson;
use App\Domains\Course\Models\LessonTask;
use App\Domains\Course\Models\LessonVoucher;
use App\Domains\User\Mails\SendVoucherMail;
use App\Domains\User\Models\User;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use Illuminate\Support\Facades\Mail;
use INTCore\OneARTFoundation\Job;
use INTCore\OneARTFoundation\run;


class AssignUserToVoucherJob extends Job
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $lesson_id;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param string $lesson_id
     */
    public function __construct(User $user, string $lesson_id)
    {
        $this->user = $user;
        $this->lesson_id = $lesson_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $voucher = LessonVoucher::where('lesson_id', $this->lesson_id)->where('user_id', $this->user->id)->first();

        if ($voucher) {
            $lesson = $voucher->lesson()->first();
            Mail::to($this->user)->queue(new SendVoucherMail($lesson->name, $voucher->voucher, $this->user,$lesson->overview));

            return $voucher;
        }
        $voucher = LessonVoucher::where('lesson_id', $this->lesson_id)->where('user_id', null)->first();
        if (!$voucher) {
            return null;
        } else {
            $lesson = $voucher->lesson()->first();
            $voucher->user_id = $this->user->id;
            $voucher->save();
            Mail::to($this->user)->queue(new SendVoucherMail($lesson->name, $voucher->voucher, $this->user,$lesson->overview));
            return $voucher;
        }
    }
}
