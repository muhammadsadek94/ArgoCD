<?php

namespace App\Domains\Notification\Http\Controllers\Api\V1;

use App\Domains\Notification\Features\Api\GetMyNotificationFeature;
use App\Domains\Notification\Features\Api\MarkAsReadNotificationFeature;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Http\Controller;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->serve(GetMyNotificationFeature::class);
    }

    public function update()
    {
        return $this->serve(MarkAsReadNotificationFeature::class);
    }
}
