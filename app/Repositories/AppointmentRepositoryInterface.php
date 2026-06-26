<?php

namespace App\Repositories;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Collection;

interface AppointmentRepositoryInterface
{
    /**
     * Obter todos os agendamentos.
     */
    public function getAll(): Collection;

    /**
     * Obter agendamento por ID.
     */
    public function getById(int $id): ?Appointment;

    /**
     * Obter agendamentos por usuário.
     */
    public function getByUserId(int $userId): Collection;

    /**
     * Obter agendamentos por status.
     */
    public function getByStatus(string $status): Collection;

    /**
     * Obter agendamentos ativos (pendente ou confirmado).
     */
    public function getActiveByUserId(int $userId): ?Appointment;

    /**
     * Criar novo agendamento.
     */
    public function create(array $data): Appointment;

    /**
     * Atualizar agendamento.
     */
    public function update(int $id, array $data): bool;

    /**
     * Deletar agendamento.
     */
    public function delete(int $id): bool;

    /**
     * Obter agendamentos com relacionamentos.
     */
    public function getWithRelations(): Collection;
}
