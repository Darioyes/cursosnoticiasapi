<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class CustomResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('http://localhost:4200/recuperar-contrasena/' . $this->token);
        return (new MailMessage)
                    ->line('Ha recibido este mensaje porque se solicitó un restablecimiento de contraseña para su cuenta.')
                    ->action('Restablecer contraseña', $url)
                    ->line('Este enlace de restablecimiento de contraseña expirará en 60 minutos. Si no ha solicitado el restablecimiento de contraseña, omita este mensaje de correo electrónico.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
