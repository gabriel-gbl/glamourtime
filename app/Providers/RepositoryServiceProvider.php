<?php

namespace App\Providers;

use App\Repositories\AppointmentRepository;
use App\Repositories\AppointmentRepositoryInterface;
use App\Repositories\AvailableSlotRepository;
use App\Repositories\AvailableSlotRepositoryInterface;
use App\Services\AppointmentService;
use App\Services\AvailableSlotService;
use App\Services\NotificationService;
use App\Services\PricingService;
use App\Strategies\NoDiscountStrategy;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Registrar Repositories
        $this->app->bind(
            AppointmentRepositoryInterface::class,
            AppointmentRepository::class
        );

        $this->app->bind(
            AvailableSlotRepositoryInterface::class,
            AvailableSlotRepository::class
        );

        // Registrar Services
        $this->app->singleton(AppointmentService::class, function ($app) {
            return new AppointmentService(
                $app->make(AppointmentRepositoryInterface::class),
                $app->make(AvailableSlotRepositoryInterface::class)
            );
        });

        $this->app->singleton(AvailableSlotService::class, function ($app) {
            return new AvailableSlotService(
                $app->make(AvailableSlotRepositoryInterface::class)
            );
        });

        $this->app->singleton(NotificationService::class, function ($app) {
            return new NotificationService();
        });

        $this->app->singleton(PricingService::class, function ($app) {
            return new PricingService(new NoDiscountStrategy());
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
