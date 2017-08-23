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
        $this->package('hieu-le/alert', 'alert', __DIR__);
    }

    public function register()
    {
        $this->app->singleton('alert', function($app) {
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
