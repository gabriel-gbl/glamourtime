<?php

namespace App\Services;

use App\Models\Appointment;
use App\Repositories\AppointmentRepositoryInterface;
use App\Repositories\AvailableSlotRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class AppointmentService
{
    protected AppointmentRepositoryInterface $appointmentRepository;
    protected AvailableSlotRepositoryInterface $slotRepository;

    public function __construct(
        AppointmentRepositoryInterface $appointmentRepository,
        AvailableSlotRepositoryInterface $slotRepository
    ) {
        $this->appointmentRepository = $appointmentRepository;
        $this->slotRepository = $slotRepository;
    }

    /**
     * Criar novo agendamento.
     *
     * @throws Exception
     */
    public function createAppointment(int $userId, string $service, string $date, string $time): Appointment
    {
        return DB::transaction(function () use ($userId, $service, $date, $time) {
            // Verificar se o horário está disponível
            $slot = $this->slotRepository->getByDateTime($date, $time);
            if (!$slot || $slot->is_booked) {
                throw new Exception('Este horário não está mais disponível.');
            }

            // Marcar horário como ocupado
            $this->slotRepository->markAsBooked($date, $time);

            // Criar agendamento
            $appointment = $this->appointmentRepository->create([
                'user_id' => $userId,
                'service' => $service,
                'date' => $date,
                'time' => substr($time, 0, 5),
                'status' => 'pendente',
            ]);

            return $appointment;
        });
    }

    /**
     * Reagendar um agendamento existente.
     *
     * @throws Exception
     */
    public function rescheduleAppointment(int $appointmentId, int $userId, string $service, string $newDate, string $newTime): Appointment
    {
        return DB::transaction(function () use ($appointmentId, $userId, $service, $newDate, $newTime) {
            // Obter agendamento
            $appointment = $this->appointmentRepository->getById($appointmentId);
            if (!$appointment || $appointment->user_id !== $userId) {
                throw new Exception('Agendamento não encontrado.');
            }

            if (!in_array($appointment->status, ['pendente', 'confirmado'])) {
                throw new Exception('Não é possível reagendar este agendamento.');
            }

            // Verificar novo horário
            $newSlot = $this->slotRepository->getByDateTime($newDate, $newTime);
            if (!$newSlot || $newSlot->is_booked) {
                throw new Exception('O novo horário não está disponível.');
            }

            // Liberar horário antigo
            $this->slotRepository->markAsAvailable($appointment->date, $appointment->time);

            // Marcar novo horário como ocupado
            $this->slotRepository->markAsBooked($newDate, $newTime);

            // Atualizar agendamento
            $this->appointmentRepository->update($appointmentId, [
                'service' => $service,
                'date' => $newDate,
                'time' => substr($newTime, 0, 5),
                'status' => 'pendente',
            ]);

            return $this->appointmentRepository->getById($appointmentId);
        });
    }

    /**
     * Cancelar agendamento.
     *
     * @throws Exception
     */
    public function cancelAppointment(int $appointmentId, int $userId): bool
    {
        return DB::transaction(function () use ($appointmentId, $userId) {
            $appointment = $this->appointmentRepository->getById($appointmentId);
            if (!$appointment || $appointment->user_id !== $userId) {
                throw new Exception('Agendamento não encontrado.');
            }

            if (!in_array($appointment->status, ['pendente', 'confirmado'])) {
                throw new Exception('Não é possível cancelar este agendamento.');
            }

            // Liberar horário
            $this->slotRepository->markAsAvailable($appointment->date, $appointment->time);

            // Cancelar agendamento
            return $this->appointmentRepository->update($appointmentId, ['status' => 'cancelado']);
        });
    }

    /**
     * Confirmar agendamento (admin).
     *
     * @throws Exception
     */
    public function confirmAppointment(int $appointmentId): bool
    {
        $appointment = $this->appointmentRepository->getById($appointmentId);
        if (!$appointment) {
            throw new Exception('Agendamento não encontrado.');
        }

        if ($appointment->status !== 'pendente') {
            throw new Exception('Apenas agendamentos pendentes podem ser confirmados.');
        }

        return $this->appointmentRepository->update($appointmentId, ['status' => 'confirmado']);
    }

    /**
     * Rejeitar agendamento (admin).
     *
     * @throws Exception
     */
    public function rejectAppointment(int $appointmentId): bool
    {
        return DB::transaction(function () use ($appointmentId) {
            $appointment = $this->appointmentRepository->getById($appointmentId);
            if (!$appointment) {
                throw new Exception('Agendamento não encontrado.');
            }

            // Liberar horário
            $this->slotRepository->markAsAvailable($appointment->date, $appointment->time);

            return $this->appointmentRepository->update($appointmentId, ['status' => 'cancelado']);
        });
    }

    /**
     * Completar agendamento (admin).
     *
     * @throws Exception
     */
    public function completeAppointment(int $appointmentId): bool
    {
        $appointment = $this->appointmentRepository->getById($appointmentId);
        if (!$appointment) {
            throw new Exception('Agendamento não encontrado.');
        }

        if ($appointment->status !== 'confirmado') {
            throw new Exception('Apenas agendamentos confirmados podem ser completados.');
        }

        // Atualizar status e adicionar pontos de fidelidade
        $this->appointmentRepository->update($appointmentId, ['status' => 'concluido']);
        $appointment->user->increment('points', 100);

        return true;
    }

    /**
     * Obter agendamento ativo do usuário.
     */
    public function getActiveAppointment(int $userId): ?Appointment
    {
        return $this->appointmentRepository->getActiveByUserId($userId);
    }

    /**
     * Obter todos os agendamentos agrupados por status.
     */
    public function getAppointmentsByStatus(): array
    {
        $appointments = $this->appointmentRepository->getWithRelations();

        return [
            'pendentes' => $appointments->where('status', 'pendente')->values(),
            'confirmados' => $appointments->where('status', 'confirmado')->values(),
            'concluidos' => $appointments->where('status', 'concluido')->values(),
            'cancelados' => $appointments->where('status', 'cancelado')->values(),
        ];
    }
}
