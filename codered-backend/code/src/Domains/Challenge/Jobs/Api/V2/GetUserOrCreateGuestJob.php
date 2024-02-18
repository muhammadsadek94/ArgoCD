<?php

namespace App\Domains\Challenge\Jobs\Api\V2;

use App\Domains\Challenge\Models\CompetitionGuest;
use App\Domains\Uploads\Models\Upload;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;

class GetUserOrCreateGuestJob extends Job
{

    private $email;
    private $first_name;
    private $last_name;
    private $display_name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $email, string $first_name = null, string $last_name = null, $display_name = null)
    {
        $this->email = $email;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->display_name = $display_name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $data = [];

        if (User::where('email', $this->email)->exists()) {
            $data['user_id'] = User::where('email', $this->email)->first()->id;
            return $data;
        }

        if (CompetitionGuest::where('email', $this->email)->exists()) {
            $data['guest_id'] = CompetitionGuest::where('email', $this->email)->first()->id;
            return $data;
        }

        $guest = CompetitionGuest::create([
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'display_name' => $this->display_name,
            'image_id'=> Upload::where('is_default_profile_image',true)->inRandomOrder()->first()?->id
        ]);

        $data['guest_id'] = $guest->id->toString();

        return $data;
    }
}
