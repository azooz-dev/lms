<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Contracts\CouponRepositoryInterface;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\CouponRepository;
use App\Repositories\CourseRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Repository interface to implementation bindings
     *
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        OrderRepositoryInterface::class => OrderRepository::class,
        PaymentRepositoryInterface::class => PaymentRepository::class,
        UserRepositoryInterface::class => UserRepository::class,
        CourseRepositoryInterface::class => CourseRepository::class,
        ReviewRepositoryInterface::class => ReviewRepository::class,
        CouponRepositoryInterface::class => CouponRepository::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        foreach ($this->bindings as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

