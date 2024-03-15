<?php

namespace Awcode\ThaiLaravel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
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
        
        Blade::directive('LaravelDompdfThaiFont', function () {
            return <<<EOT
<style>
@font-face {
    font-family: 'THSarabunNew';
    font-style: normal;
    font-weight: normal;
    src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype');
}
@font-face {
    font-family: 'THSarabunNew';
    font-style: normal;
    font-weight: bold;
    src: url("{{ public_path('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
}
@font-face {
    font-family: 'THSarabunNew';
    font-style: italic;
    font-weight: normal;
    src: url("{{ public_path('fonts/THSarabunNew Italic.ttf') }}") format('truetype');
}
@font-face {
    font-family: 'THSarabunNew';
    font-style: italic;
    font-weight: bold;
    src: url("{{ public_path('fonts/THSarabunNew BoldItalic.ttf') }}") format('truetype');
}
</style>
EOT;
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
