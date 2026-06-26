<?php

namespace App\Strategies;

interface DiscountStrategy
{
    /**
     * Calcular desconto baseado na estratégia.
     */
    public function calculate(float $basePrice): float;

    /**
     * Obter descrição da estratégia.
     */
    public function getDescription(): string;
}
