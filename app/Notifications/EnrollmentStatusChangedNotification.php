<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\BankDetail;

class EnrollmentStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $enrollment;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( $enrollment)
    {
        $this->enrollment = $enrollment;
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
        $bankDetail = $this->bankInfo();
        if($this->enrollment->status == 'approved')
        {
            return (new MailMessage)
                    ->subject('Enrollment for '.$this->enrollment->course->title.' Approved')
                    ->line('Your enrollment for '.$this->enrollment->course->title.' has been aproved, Pay the course fee to LABS bank account below,
                     and upload payment details to your dashboard')
                    ->line('Amount : '.$this->enrollment->course->price)
                    ->line('Account Name : '.$bankDetail['accountName'])
                    ->line('Bank Account Number : '.$bankDetail['accountNumber'])
                    ->line('Bank Name : '.$bankDetail['bankName'])
                    ->action('My Dashboard', $url);
        }
        return (new MailMessage)
                    ->subject('Enrollment for '.$this->enrollment->course->title.' '.$this->enrollment->status)
                    ->line('Your enrollment for '.$this->enrollment->course->title.' has been '.$this->enrollment->status.'')
                    ->line('Reason(s): '.$this->enrollment->admin_note)
                    ->action('My Dashboard', $url);
    }

    public function bankInfo()
    {
      $bankDetail = BankDetail::whereId(1)->get();
      if ($bankDetail->count() > 0){
          return $bankDetail->first();
      }
        return config('system.bankDetails');
    }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        if($this->enrollment->status == 'Approved')
        {
            return [
            'type' => 'success',
            'title' => 'Enrollment Approved',
            'body' => 'Your enrollment for '.$this->enrollment->course->title.' has been approved by LABS official, proceed to make payment to the bank account sent to your email',
            'other'=> ''
            ];
        }

        return [
            'type' => 'error',
            'title' => 'Enrollment '.$this->enrollment->status,
            'body' => 'Your enrollment for '.$this->enrollment->course->title.' has been '.$this->enrollment->status. ' by LABS official',
            'other' => $this->enrollment->admin_note,
        ];

    }
}
