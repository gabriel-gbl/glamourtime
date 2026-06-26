<?php

namespace App\Repositories;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Collection;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    /**
     * Obter todos os agendamentos.
     */
    public function getAll(): Collection
    {
        return Appointment::all();
    }

    /**
     * Obter agendamento por ID.
     */
    public function getById(int $id): ?Appointment
    {
        return Appointment::find($id);
    }

    /**
     * Obter agendamentos por usuário.
     */
    public function getByUserId(int $userId): Collection
    {
        return Appointment::where('user_id', $userId)->get();
    }

    /**
     * Obter agendamentos por status.
     */
    public function getByStatus(string $status): Collection
    {
        return Appointment::where('status', $status)->get();
    }

    /**
     * Obter agendamentos ativos (pendente ou confirmado).
     */
    public function getActiveByUserId(int $userId): ?Appointment
    {
        return Appointment::where('user_id', $userId)
            ->whereIn('status', ['pendente', 'confirmado'])
            ->orderBy('date')
            ->orderBy('time')
            ->first();
    }

    /**
     * Criar novo agendamento.
     */
    public function create(array $data): Appointment
    {
        return Appointment::create($data);
    }

    /**
     * Atualizar agendamento.
     */
    public function update(int $id, array $data): bool
    {
        $appointment = $this->getById($id);
        if (!$appointment) {
            return false;
        }
        return $appointment->update($data);
    }

    /**
     * Deletar agendamento.
     */
    public function delete(int $id): bool
    {
        $appointment = $this->getById($id);
        if (!$appointment) {
            return false;
        }
        return $appointment->delete();
    }

    /**
     * Obter agendamentos com relacionamentos.
     */
    public function getWithRelations(): Collection
    {
        return Appointment::with('user')->orderBy('date')->orderBy('time')->get();
    }
}
