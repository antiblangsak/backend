<?php
/**
 * Created by PhpStorm.
 * User: Syukri
 * Date: 6/25/18
 * Time: 12:25 AM
 */

namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class MyResetPasswordNotification extends Notification
{
    use Queueable;
    public $token = "";
    public $email = "";

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from('forget-password@antiblangsak.com', '[NO-REPLY] Reset Password Antiblangsak')
            ->greeting('Halo!')
            ->subject('[Antiblangsak] Reset Password')
            ->line('Anda menerima email ini karena kami menerima permintaan reset password untuk Akun anda.')
            ->line('Berikut ini adalah kode unik yang dapat Anda gunakan untuk me-reset password Anda:')
            ->line(strtoupper($this->token))
            ->line('Jika Anda tidak mengirimkan permintaan reset password dalam waktu dekat, Anda dapat mengabaikan email ini.')
            ->with('Terima kasih,')
            ->salutation('Tim Antiblangsak');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
