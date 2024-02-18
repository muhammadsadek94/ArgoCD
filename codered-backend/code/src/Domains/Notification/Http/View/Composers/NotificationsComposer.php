<?php


namespace App\Domains\Notification\Http\View\Composers;

use Illuminate\View\View;
use Auth;

class NotificationsComposer
{
    public function compose(View $view)
    {
        $view->with('notifications', Auth::guard("provider")->user()->notifications->load('notifier'));
    }
}
