<?php

namespace HieuLe\Alert;

use Illuminate\Support\ServiceProvider;

/**
 * Description of AlertServiceProvider
 *
 * @author Hieu Le <letrunghieu.cse09@gmail.com>
 */
class AlertServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . "/views", 'alert');
        $this->loadTranslationsFrom(__DIR__ . "/lang", 'alert');
        $this->mergeConfigFrom(__DIR__ . "/config", 'alert');
    }

    public function register()
    {
        $this->app['alert'] = $this->app->share(function($app) {
            return new Alert($app['session.store'], $app['config'], $app['view']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('alert');
    }

}
