<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Enviar notificação.
     */
    public function notify(array $notification): void
    {
        // Log da notificação
        Log::info('Notificação enviada', [
            'type' => $notification['type'] ?? 'unknown',
            'user_id' => $notification['user_id'] ?? null,
            'title' => $notification['title'] ?? '',
            'message' => $notification['message'] ?? '',
        ]);

        // Aqui você pode integrar com serviços reais de notificação
        // Por exemplo: SendGrid, Twilio, Firebase, etc.

        // Exemplo: Enviar email
        // Mail::to($user->email)->send(new AppointmentNotification($notification));

        // Exemplo: Enviar SMS
        // Twilio::sendSMS($user->phone, $notification['message']);

        // Exemplo: Push notification
        // Firebase::sendPushNotification($user->device_token, $notification);
    }

    /**
     * Enviar notificação por email.
     */
    public function notifyByEmail(string $email, string $subject, string $message): void
    {
        Log::info('Email enviado', [
            'to' => $email,
            'subject' => $subject,
            'message' => $message,
        ]);

        // Mail::to($email)->send(new NotificationMail($subject, $message));
    }

    /**
     * Enviar notificação por SMS.
     */
    public function notifyBySMS(string $phone, string $message): void
    {
        Log::info('SMS enviado', [
            'to' => $phone,
            'message' => $message,
        ]);

        // Twilio::sendSMS($phone, $message);
    }

    /**
     * Enviar notificação push.
     */
    public function notifyByPush(int $userId, string $title, string $message): void
    {
        Log::info('Push notification enviada', [
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
        ]);

        // Firebase::sendPushNotification($userId, $title, $message);
    }
}
