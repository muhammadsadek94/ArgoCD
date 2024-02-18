<?php

namespace App\Domains\User\Listeners\User\Portal;

use App\Domains\User\Events\User\PasswordUpdated;
use App\Domains\User\Events\User\UserCreated;
use App\Domains\User\Events\User\UserUpdated;
use App\Domains\User\Services\UpdatePasswordServices;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdatePasswordIntegrationListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PasswordUpdated  $event
     * @return void
     */
    public function handle(PasswordUpdated $event)
    {
        $request = new UpdatePasswordServices();
        if(config('app.env')!='local'){
            $response = $request->sendMessageForUpdatedPasswordEvent($event);
//            Log::info('updatePassword'.  collect($response)->toJson());
        }
    }
}
