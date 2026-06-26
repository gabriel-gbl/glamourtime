<?php

namespace App\Repositories;

use App\Models\AvailableSlot;
use Illuminate\Database\Eloquent\Collection;

interface AvailableSlotRepositoryInterface
{
    /**
     * Obter todos os horários disponíveis.
     */
    public function getAll(): Collection;

    /**
     * Obter horários disponíveis (não marcados).
     */
    public function getAvailable(): Collection;

    /**
     * Obter horário por data e hora.
     */
    public function getByDateTime(string $date, string $time): ?AvailableSlot;

    /**
     * Criar novo horário disponível.
     */
    public function create(array $data): AvailableSlot;

    /**
     * Atualizar horário.
     */
    public function update(int $id, array $data): bool;

    /**
     * Marcar horário como ocupado.
     */
    public function markAsBooked(string $date, string $time): bool;

    /**
     * Liberar horário.
     */
    public function markAsAvailable(string $date, string $time): bool;

    /**
     * Verificar se horário existe.
     */
    public function exists(string $date, string $time): bool;

    /**
     * Deletar horário.
     */
    public function delete(int $id): bool;
}
