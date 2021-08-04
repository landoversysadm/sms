<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewMaterialNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $courseTitle;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($courseTitle)
    {
      $this->courseTitle = $courseTitle;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
      $url = url('/student');
        return (new MailMessage)
              ->subject('New Course Material Alert')
              ->line('New material for '.$this->courseTitle.' uploaded, visit your dashboard to download')
              ->action('My Dashboard', $url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
          'type' => 'success',
          'title' => 'New Course Material Alert',
          'body' => 'New material for '.$this->courseTitle.' uploaded, you can download from dashboard',
          'other'=> ''
        ];
    }
}
