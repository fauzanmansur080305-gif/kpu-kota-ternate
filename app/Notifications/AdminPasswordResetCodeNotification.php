<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminPasswordResetCodeNotification extends Notification
{
    public function __construct(public readonly string $code) {}

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
        return (new MailMessage)
            ->subject('Kode Verifikasi Pemulihan Kata Sandi')
            ->view([
                'html' => 'emails.admin-password-reset-code',
                'text' => 'emails.admin-password-reset-code-text',
            ], ['code' => $this->code])
            ->withSymfonyMessage(function (\Symfony\Component\Mime\Email $message): void {
                $message->addPart(
                    (new \Symfony\Component\Mime\Part\DataPart(
                        new \Symfony\Component\Mime\Part\File(public_path('kpu-logo.png')),
                        'logo-kpu',
                        'image/png',
                    ))
                        ->asInline()
                        ->setContentId('logo-kpu@kpu-kotaternate'),
                );
            });
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [];
    }
}
