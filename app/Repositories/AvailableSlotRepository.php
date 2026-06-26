<?php

namespace App\Repositories;

use App\Models\AvailableSlot;
use Illuminate\Database\Eloquent\Collection;

class AvailableSlotRepository implements AvailableSlotRepositoryInterface
{
    /**
     * Obter todos os horários disponíveis.
     */
    public function getAll(): Collection
    {
        return AvailableSlot::orderBy('date')->orderBy('time')->get();
    }

    /**
     * Obter horários disponíveis (não marcados).
     */
    public function getAvailable(): Collection
    {
        return AvailableSlot::where('is_booked', false)
            ->orderBy('date')
            ->orderBy('time')
            ->get();
    }

    /**
     * Obter horário por data e hora.
     */
    public function getByDateTime(string $date, string $time): ?AvailableSlot
    {
        return AvailableSlot::where('date', $date)
            ->where('time', $time)
            ->first();
    }

    /**
     * Criar novo horário disponível.
     */
    public function create(array $data): AvailableSlot
    {
        return AvailableSlot::create($data);
    }

    /**
     * Atualizar horário.
     */
    public function update(int $id, array $data): bool
    {
        $slot = AvailableSlot::find($id);
        if (!$slot) {
            return false;
        }
        return $slot->update($data);
    }

    /**
     * Marcar horário como ocupado.
     */
    public function markAsBooked(string $date, string $time): bool
    {
        return AvailableSlot::where('date', $date)
            ->where('time', $time)
            ->update(['is_booked' => true]) > 0;
    }

    /**
     * Liberar horário.
     */
    public function markAsAvailable(string $date, string $time): bool
    {
        return AvailableSlot::where('date', $date)
            ->where('time', $time)
            ->update(['is_booked' => false]) > 0;
    }

    /**
     * Verificar se horário existe.
     */
    public function exists(string $date, string $time): bool
    {
        return AvailableSlot::where('date', $date)
            ->where('time', $time)
            ->exists();
    }

    /**
     * Deletar horário.
     */
    public function delete(int $id): bool
    {
        $slot = AvailableSlot::find($id);
        if (!$slot) {
            return false;
        }
        return $slot->delete();
    }
}
