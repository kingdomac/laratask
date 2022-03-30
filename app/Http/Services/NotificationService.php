<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as FacadeNotfication;

class NotificationService
{

    public static function notifyUser(User| Collection $toNotify, Notification $notification)
    {

        if ($toNotify instanceof Collection) {
            return FacadeNotfication::send($toNotify, $notification);
        }
        return FacadeNotfication::send($toNotify, $notification);
    }
}
