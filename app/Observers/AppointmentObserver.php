<?php

namespace App\Observers;

use App\Factories\NotificationFactory;
use App\Models\Appointment;
use App\Services\NotificationService;

class AppointmentObserver
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Disparado quando um agendamento é criado.
     */
    public function created(Appointment $appointment): void
    {
        // Notificar sobre novo agendamento
        $notification = NotificationFactory::createConfirmedNotification($appointment);
        $this->notificationService->notify($notification);
    }

    /**
     * Disparado quando um agendamento é atualizado.
     */
    public function updated(Appointment $appointment): void
    {
        // Verificar mudanças de status
        if ($appointment->isDirty('status')) {
            $oldStatus = $appointment->getOriginal('status');
            $newStatus = $appointment->status;

            if ($oldStatus === 'pendente' && $newStatus === 'confirmado') {
                $notification = NotificationFactory::createConfirmedNotification($appointment);
                $this->notificationService->notify($notification);
            } elseif ($newStatus === 'cancelado') {
                $notification = NotificationFactory::createCancelledNotification($appointment);
                $this->notificationService->notify($notification);
            } elseif ($newStatus === 'concluido') {
                $notification = NotificationFactory::createCompletedNotification($appointment);
                $this->notificationService->notify($notification);
            }
        }
    }

    /**
     * Disparado quando um agendamento é deletado.
     */
    public function deleted(Appointment $appointment): void
    {
        // Notificar sobre exclusão
        $notification = NotificationFactory::createCancelledNotification($appointment);
        $this->notificationService->notify($notification);
    }
}
