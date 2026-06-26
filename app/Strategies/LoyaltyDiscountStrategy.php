<?php

namespace App\Strategies;

class LoyaltyDiscountStrategy implements DiscountStrategy
{
    private int $userPoints;
    private float $pointValue;

    /**
     * Construtor com pontos do usuário.
     */
    public function __construct(int $userPoints = 0, float $pointValue = 0.01)
    {
        $this->userPoints = $userPoints;
        $this->pointValue = $pointValue; // Valor em reais por ponto
    }

    /**
     * Calcular desconto baseado na estratégia.
     * Oferece desconto proporcional aos pontos de fidelidade.
     */
    public function calculate(float $basePrice): float
    {
        $discountAmount = $this->userPoints * $this->pointValue;
        $finalPrice = $basePrice - $discountAmount;

        // Garantir que o preço não seja negativo
        return max($finalPrice, 0);
    }

    /**
     * Obter descrição da estratégia.
     */
    public function getDescription(): string
    {
        $discount = $this->userPoints * $this->pointValue;
        return "Desconto por fidelidade: R$ {$discount} ({$this->userPoints} pontos)";
    }
}
