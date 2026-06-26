<?php

namespace App\Strategies;

class NoDiscountStrategy implements DiscountStrategy
{
    /**
     * Calcular desconto baseado na estratégia (sem desconto).
     */
    public function calculate(float $basePrice): float
    {
        return $basePrice;
    }

    /**
     * Obter descrição da estratégia.
     */
    public function getDescription(): string
    {
        return 'Sem desconto';
    }
}
