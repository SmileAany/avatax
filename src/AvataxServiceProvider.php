<?php

namespace Smbear\Avatax;

use Illuminate\Support\ServiceProvider;
use Smbear\Avatax\Contracts\AvataxInterface;
use Smbear\Avatax\Providers\EventServiceProvider;

class AvataxServiceProvider extends ServiceProvider
{
    public function boot()
    {
         $this->publishes([
             __DIR__.'/../config/avatax.php' => config_path('avatax.php'),
         ], 'config');

         $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'avatax');

         $this->publishes([
             __DIR__.'/../resources/lang' => resource_path('lang'),
         ], 'translations');

         $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

         $this->publishes([
             __DIR__.'/../database/migrations/' => database_path('migrations')
         ], 'migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(EventServiceProvider::class);

        $this->app->singleton('avatax',function($app){
            return new Avatax();
        });

        $this->app->bind(AvataxInterface::class,Avatax::class);

        $this->mergeConfigFrom(
            __DIR__.'/../config/avatax.php', 'avatax'
        );
    }
}
