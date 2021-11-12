<?php

namespace Awcode\ThaiLaravel;

use Illuminate\Support\ServiceProvider;
use Awcode\ThaiLaravel\Commands\InstallCommand;

class ThaiLaravelServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('thai-laravel',function(){
            return new \Awcode\ThaiLaravel\ThaiLaravel();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/thai-laravel.php' => config_path('thai-laravel.php'),
        ], 'config');
        
        $this->publishes([
            __DIR__.'/../fonts' => public_path('th-fonts'),
        ], 'fonts');
        
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class
            ]);
        }
    }
}
//Service provider options listed @ https://laravel.com/docs/8.x/packages
