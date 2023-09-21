<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminNotification extends Notification
{
    use Queueable;

    public $user_id;
    public $order_id;
    public $contact_id;

    public function __construct($user_id, $order_id, $contact_id)
    {
        $this->user_id = $user_id;
        $this->order_id = $order_id;
        $this->contact_id = $contact_id;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user_id,
            'order_id' => $this->order_id,
            'contact_id' => $this->contact_id,
        ];
    }
}
