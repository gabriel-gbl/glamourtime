<?php

namespace App\Services;

use App\Models\AvailableSlot;
use App\Repositories\AvailableSlotRepositoryInterface;
use Exception;

class AvailableSlotService
{
    protected AvailableSlotRepositoryInterface $repository;

    public function __construct(AvailableSlotRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Criar novo horário disponível.
     *
     * @throws Exception
     */
    public function createSlot(string $date, string $time): AvailableSlot
    {
        // Validar se o horário já existe
        if ($this->repository->exists($date, $time)) {
            throw new Exception('Este horário já está cadastrado para este dia.');
        }

        return $this->repository->create([
            'date' => $date,
            'time' => $time,
            'is_booked' => false,
        ]);
    }

    /**
     * Obter todos os horários disponíveis.
     */
    public function getAvailableSlots()
    {
        return $this->repository->getAvailable();
    }

    /**
     * Obter todos os horários.
     */
    public function getAllSlots()
    {
        return $this->repository->getAll();
    }

    /**
     * Deletar horário.
     *
     * @throws Exception
     */
    public function deleteSlot(int $id): bool
    {
        if (!$this->repository->delete($id)) {
            throw new Exception('Horário não encontrado.');
        }
        return true;
    }
}
