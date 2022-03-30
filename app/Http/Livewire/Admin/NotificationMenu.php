<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\DatabaseNotification;

class NotificationMenu extends Component
{
    public $notifications;
    public $countUnreadNotifications;



    public function countUnreadNotifications()
    {
        $this->countUnreadNotifications = count(auth()->user()->unreadNotifications);
    }

    public function markAsRead($notificationId)
    {
        //$this->notifications->markAsRead();
        $notification = DatabaseNotification::where('id', $notificationId)->first();
        if (!$notification->read_at) {
            $notification->read_at = now();
            $notification->save();
        }
        return redirect()->route('admin.projects.issues.show', [$notification->data['project_id'], $notification->data['id']]);
    }

    public function clearAll()
    {
        DB::transaction(function () {
            auth()->user()->notifications()->sharedLock();
            auth()->user()->notifications()->delete();
        });
        $this->notifications = [];
    }

    public function delete($notificationId)
    {
        DatabaseNotification::where('id', $notificationId)->delete();
        $this->countUnreadNotifications ? $this->countUnreadNotifications - 1 : 0;
    }

    public function render()
    {
        $this->notifications = auth()->user()->notifications;
        $this->countUnreadNotifications = count(auth()->user()->unreadNotifications);
        return view('livewire.admin.notification-menu');
    }
}
