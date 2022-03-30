<?php

namespace App\Notifications;

use App\Models\Issue;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewIssueCreated extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $issue;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($issue)
    {
        $this->issue = $issue;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        //return ['mail', 'database'];
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
            ->line(__('New Issue has been created'))
            ->action('click here to view the new issue', url('/'))
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
    //         //
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
            'message' => __('New ' . $this->issue->label->name . ' has been created')
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'message' => "hello (User $notifiable->id)"
        ]);
    }
}
