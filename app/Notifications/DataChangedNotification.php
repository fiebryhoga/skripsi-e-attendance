<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DataChangedNotification extends Notification
{
    use Queueable;

    private $message;
    private $type;

    public function __construct($message, $type = 'success')
    {
        $this->message = $message;
        $this->type = $type;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->message,
            'type' => $this->type,
            'time' => now()->diffForHumans(),
        ];
    }
}