<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Payment;

class PaymentStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $payment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($payment)
    {
        $this->payment = $payment;
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
        if($this->payment->validated == 2) {
            return (new MailMessage)
                ->subject('Payment for '.$this->payment->enrollment->course->title.' Validated')
                ->line('Your recent payment upload for the course '.$this->payment->enrollment->course->title.' has been validated, you are now enrolled for the course')
                ->line('Visit your student dashboard to view other information')
                ->action('My Dashboard', $url);
        }

        return (new MailMessage)
            ->subject('Payment for '.$this->payment->enrollment->course->title.' Rejected')
            ->line('Your recent payment upload for the course '.$this->payment->enrollment->course->title.' was rejected by the school adminstrator because of the reason(s) stated below')
            ->line('Reason(s) : '.$this->payment->admin_note)
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
        if($this->payment->validated == 2) {
            return [
                'type' => 'success',
                'title' => 'Payment Validated',
                'body' => 'Your recent payment upload for the course '.$this->payment->enrollment->course->title.' has been validated, you are now enrolled for the course',
                'other'=> ''
            ];
        }

        return [
            'type' => 'error',
            'title' => 'Payment Rejected',
            'body' => 'Your recent payment upload for the course '.$this->payment->enrollment->course->title.' was rejected by the school adminstrator, check the admin note for reason',
            'other'=> $this->payment->admin_note
        ];

    }
}
