<?php


namespace SzuniSoft\Mnb\Laravel;


use Illuminate\Support\ServiceProvider;

/**
 * Class MnbServiceProvider
 * @package SzuniSoft\Mnb\Laravel
 */
class MnbServiceProvider extends ServiceProvider {

    /**
     * Register service provider
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'mnb-exchange');

        $this->registerClient();
    }

    /**
     *
     */
    protected function registerClient()
    {
        $this->app->singleton(Client::class, function ($app) {
            $config = $app['config']['mnb-exchange'];
            $cacheStore = $app['cache']->store($config['cache']['store']);
            $mnbClient = new \SzuniSoft\Mnb\Client($config['wsdl']);
            return new Client($mnbClient, $cacheStore,$config['cache']['minutes']);
        });

        $this->app->alias(Client::class, 'mnb.client');
    }

    /**
     * Boot service provider
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/../config/config.php'], 'config');
    }

}