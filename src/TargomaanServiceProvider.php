<?php

namespace Armincms\Targomaan;
 
use Illuminate\Support\ServiceProvider; 

class TargomaanServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    { 
    } 

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('targoman', function($app) {
            return new Targomaan($app);
        });
    }
}
