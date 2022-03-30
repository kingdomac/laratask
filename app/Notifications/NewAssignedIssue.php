<?php

namespace App\Notifications;

use App\Models\Issue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewAssignedIssue extends Notification implements ShouldBroadcast
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public Issue $issue)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line(__('you have been assigned a new issue'))
            ->action('check the assigned issue', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    // public function toArray($notifiable)
    // {
    //     return [
    //         'id' => $this->issue->id,
    //         'title' => $this->issue->title,
    //         'label_id' => $this->issue->label_id,
    //         'label_name' => $this->issue->label->label_name,
    //         'project_id' => $this->issue->project_id,
    //         'project_name' => $this->issue->project->name,
    //         'parent_id' => $this->issue->parent_id,
    //         'message' => __('you have been assigned a new ' . $this->issue->label->name)
    //     ];
    // }

    public function toDatabase($notifiable)
    {
        return [
            'id' => $this->issue->id,
            'title' => $this->issue->title,
            'label_id' => $this->issue->label_id,
            'label_name' => $this->issue->label->label_name,
            'project_id' => $this->issue->project_id,
            'project_name' => $this->issue->project->name,
            'parent_id' => $this->issue->parent_id,
            'message' => __('you have been assigned a new ' . $this->issue->label->name)
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'message' => "hello (User $notifiable->id)"
        ]);
    }
}
