<?php

namespace App\Providers;

use App\Repositories\EloquentTaskRepository;
use App\Repositories\EloquentTaskStatusRepository;
use App\Repositories\TaskRepositoryInterface;
use App\Repositories\TaskStatusRepositoryInterface;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TaskRepositoryInterface::class, EloquentTaskRepository::class);
        $this->app->bind(TaskStatusRepositoryInterface::class, EloquentTaskStatusRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(190);
    }
}
