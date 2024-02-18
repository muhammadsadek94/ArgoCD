<?php

namespace App\Domains\Notification\Http\Controllers\Provider;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use INTCore\OneARTFoundation\Http\Controller;

class ProviderNotificationController extends Controller
{
    public function NotificatioRead()
    {
        $user = Auth::guard('provider')->user();
        foreach ($user->notifications as $notification){
            $notification->markAsRead();
        }
        return response()->json(["status" => "success"]);
    }
}
