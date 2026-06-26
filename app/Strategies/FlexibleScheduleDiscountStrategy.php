<?php

namespace App\Strategies;

class FlexibleScheduleDiscountStrategy implements DiscountStrategy
{
    private float $discountPercentage;

    /**
     * Construtor com percentual de desconto.
     */
    public function __construct(float $discountPercentage = 10)
    {
        $this->discountPercentage = $discountPercentage;
    }

    /**
     * Calcular desconto baseado na estratégia.
     * Oferece desconto para horários flexíveis (ex: fora do horário de pico).
     */
    public function calculate(float $basePrice): float
    {
        $discount = $basePrice * ($this->discountPercentage / 100);
        return $basePrice - $discount;
    }

    /**
     * Obter descrição da estratégia.
     */
    public function getDescription(): string
    {
        return "Desconto de {$this->discountPercentage}% para horários flexíveis";
    }
}
