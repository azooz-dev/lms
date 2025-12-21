<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\BlogCategoryRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\Contracts\BlogCategoryRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\CouponRepositoryInterface;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\Contracts\QuestionRepositoryInterface;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\SettingRepositoryInterface;
use App\Repositories\Contracts\SubCategoryRepositoryInterface;
use App\Repositories\Contracts\TagRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\WishListRepositoryInterface;
use App\Repositories\CouponRepository;
use App\Repositories\CourseRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\PostRepository;
use App\Repositories\QuestionRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\RoleRepository;
use App\Repositories\SettingRepository;
use App\Repositories\SubCategoryRepository;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use App\Repositories\WishListRepository;
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
        CategoryRepositoryInterface::class => CategoryRepository::class,
        SubCategoryRepositoryInterface::class => SubCategoryRepository::class,
        BlogCategoryRepositoryInterface::class => BlogCategoryRepository::class,
        PostRepositoryInterface::class => PostRepository::class,
        TagRepositoryInterface::class => TagRepository::class,
        RoleRepositoryInterface::class => RoleRepository::class,
        PermissionRepositoryInterface::class => PermissionRepository::class,
        SettingRepositoryInterface::class => SettingRepository::class,
        QuestionRepositoryInterface::class => QuestionRepository::class,
        WishListRepositoryInterface::class => WishListRepository::class,
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
