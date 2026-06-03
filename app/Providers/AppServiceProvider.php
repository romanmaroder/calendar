<?php

namespace App\Providers;

use App\Repositories\Contracts\BranchRepositoryInterface;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\EloquentBranchRepository;
use App\Repositories\EloquentCompanyRepository;
use App\Repositories\EloquentUserRepository;
use App\Services\BranchUserService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(BranchUserService::class, function ($app) {
            return new BranchUserService(
                $app->make(UserRepositoryInterface::class)
            );
        });
        $this->app->bind(BranchRepositoryInterface::class,EloquentBranchRepository::class);
        $this->app->bind(CompanyRepositoryInterface::class,EloquentCompanyRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventSilentlyDiscardingAttributes($this->app->isLocal());
    }
}
