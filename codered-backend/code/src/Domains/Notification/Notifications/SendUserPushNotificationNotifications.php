<?php

namespace App\Domains\Notification\Notifications;

use App\Domains\Notification\Broadcasting\DatabaseChannel;
use App\Domains\Notification\Enum\NotificationType;
use App\Domains\Notification\Logic\FcmMessage;
use App\Domains\Notification\Models\Data\CustomData;
use App\Domains\Notification\Models\Data\NotificationMessage;
use App\Domains\Notification\Models\Data\NotificationSetting;
use App\Domains\Services\Models\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Http\Request;
use Auth;

class SendUserPushNotificationNotifications extends Notification
{
    use Queueable;

    public $notifier_id = null;

    public $request;
    public $action_type;
    public $action_id = null;

    /**
     * Create a new notification instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->action_id = 0;
        $this->action_type = NotificationType::ADMIN;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['fcm', DatabaseChannel::class];

    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return
     */
    public function toFcm($notifiable)
    {
        $icon = "/imgs/logo.png";
        //        $click_action = url('provider/service');

        $message = new NotificationMessage();
        // message
        $message->setTitle($this->request->title_en, 'en');
        $message->setBody($this->request->body_en, 'en');
        $message->setTitle($this->request->title_en, 'ar');
        $message->setBody($this->request->body_ar, 'ar');


        // settings
        $settings = (new NotificationSetting())
            ->setIcon($icon);
        //            ->setClickAction($click_action);
        //
        //        $custom_data = (new CustomData($this->action_id, $this->action_type))
        //            ->setData('user', $this->service->provider)
        //            ->setData('test', 'asd');


        $fcmMessage = new FcmMessage();
        $fcmMessage->setSettings($settings)
            ->setMessage($message)
            //            ->setData($custom_data)
            ->priority(FcmMessage::PRIORITY_HIGH); // Optional - Default is 'normal'.

        return $fcmMessage;

    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            "title_en" => $this->request->title_en,
            "title_ar" => $this->request->title_ar,
            "body_en"  => $this->request->title_en,
            "body_ar"  => $this->request->title_ar,
        ];
    }
}
