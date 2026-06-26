<?php

namespace App\Factories;

use App\Models\Appointment;
use App\Models\User;

class NotificationFactory
{
    /**
     * Criar notificação de agendamento confirmado.
     */
    public static function createConfirmedNotification(Appointment $appointment): array
    {
        return [
            'type' => 'appointment_confirmed',
            'user_id' => $appointment->user_id,
            'title' => 'Agendamento Confirmado',
            'message' => "Seu agendamento para {$appointment->date} às {$appointment->time} foi confirmado!",
            'appointment_id' => $appointment->id,
            'data' => [
                'date' => $appointment->date,
                'time' => $appointment->time,
                'service' => $appointment->service,
            ],
        ];
    }

    /**
     * Criar notificação de agendamento cancelado.
     */
    public static function createCancelledNotification(Appointment $appointment): array
    {
        return [
            'type' => 'appointment_cancelled',
            'user_id' => $appointment->user_id,
            'title' => 'Agendamento Cancelado',
            'message' => "Seu agendamento para {$appointment->date} às {$appointment->time} foi cancelado.",
            'appointment_id' => $appointment->id,
            'data' => [
                'date' => $appointment->date,
                'time' => $appointment->time,
            ],
        ];
    }

    /**
     * Criar notificação de agendamento concluído.
     */
    public static function createCompletedNotification(Appointment $appointment): array
    {
        return [
            'type' => 'appointment_completed',
            'user_id' => $appointment->user_id,
            'title' => 'Atendimento Concluído',
            'message' => "Seu atendimento de {$appointment->service} foi concluído com sucesso! Você ganhou 100 pontos de fidelidade.",
            'appointment_id' => $appointment->id,
            'data' => [
                'service' => $appointment->service,
                'points_earned' => 100,
            ],
        ];
    }

    /**
     * Criar notificação de lembrete de agendamento.
     */
    public static function createReminderNotification(Appointment $appointment): array
    {
        return [
            'type' => 'appointment_reminder',
            'user_id' => $appointment->user_id,
            'title' => 'Lembrete de Agendamento',
            'message' => "Você tem um agendamento amanhã às {$appointment->time} para {$appointment->service}.",
            'appointment_id' => $appointment->id,
            'data' => [
                'date' => $appointment->date,
                'time' => $appointment->time,
                'service' => $appointment->service,
            ],
        ];
    }

    /**
     * Criar notificação de novo horário disponível.
     */
    public static function createNewSlotNotification(string $date, string $time): array
    {
        return [
            'type' => 'new_slot_available',
            'title' => 'Novo Horário Disponível',
            'message' => "Um novo horário foi adicionado: {$date} às {$time}",
            'data' => [
                'date' => $date,
                'time' => $time,
            ],
        ];
    }

    /**
     * Criar dados de teste para usuário.
     */
    public static function createTestUserData(): array
    {
        return [
            'name' => 'Cliente Teste',
            'email' => 'cliente.teste@example.com',
            'password' => bcrypt('password123'),
            'phone' => '(11) 99999-9999',
            'role' => 'client',
        ];
    }

    /**
     * Criar dados de teste para agendamento.
     */
    public static function createTestAppointmentData(int $userId, string $date, string $time): array
    {
        return [
            'user_id' => $userId,
            'service' => 'Manicure Completa',
            'date' => $date,
            'time' => $time,
            'status' => 'pendente',
        ];
    }
}
