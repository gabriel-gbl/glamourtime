<?php

namespace App\Services;

use App\Strategies\DiscountStrategy;
use App\Strategies\NoDiscountStrategy;

class PricingService
{
    private DiscountStrategy $discountStrategy;

    public function __construct(DiscountStrategy $discountStrategy = null)
    {
        $this->discountStrategy = $discountStrategy ?? new NoDiscountStrategy();
    }

    /**
     * Definir estratégia de desconto.
     */
    public function setDiscountStrategy(DiscountStrategy $strategy): void
    {
        $this->discountStrategy = $strategy;
    }

    /**
     * Calcular preço final com desconto.
     */
    public function calculatePrice(float $basePrice): float
    {
        return $this->discountStrategy->calculate($basePrice);
    }

    /**
     * Obter descrição do desconto aplicado.
     */
    public function getDiscountDescription(): string
    {
        return $this->discountStrategy->getDescription();
    }

    /**
     * Calcular valor do desconto.
     */
    public function calculateDiscount(float $basePrice): float
    {
        $finalPrice = $this->discountStrategy->calculate($basePrice);
        return $basePrice - $finalPrice;
    }
}
