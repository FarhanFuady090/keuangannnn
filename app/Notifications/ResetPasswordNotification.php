<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    public $token;
    public $role;

    public function __construct($token, $role)
    {
        $this->token = $token;
        $this->role = $role;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Tentukan base path berdasarkan role
        $basePath = match ($this->role) {
            'admin'   => '/admin',
            'tupusat' => '/tupusat',
            'tuunit'  => '/tuunit',
            default   => '/',
        };

        $url = url($basePath . '/reset-password/' . $this->token . '?email=' . urlencode($notifiable->email));

        return (new MailMessage)
            ->subject('Reset Password Anda')
            ->greeting('Halo!')
            ->line('Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.')
            ->action('Reset Password', $url)
            ->line('Link reset password ini akan kedaluwarsa dalam 60 menit.')
            ->line('Jika Anda tidak meminta reset password, abaikan email ini.')
            ->salutation('Hormat kami, Tim Keuangan');
    }
}
