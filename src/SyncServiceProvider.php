<?php

namespace Trax\Sync;

use Trax\Foundation\TraxServiceProvider;

class SyncServiceProvider extends TraxServiceProvider
{
    /**
     * Plugin code. 
     */
    protected $plugin = 'trax-sync';

    /**
     * Namespace. 
     */
    protected $namespace = __NAMESPACE__;

    /**
     * Directory. 
     */
    protected $dir = __DIR__;

    /**
     * Register migrations.
     */
    protected $hasMigrations = true;

    /**
     * Register config file.
     */
    protected $hasConfig = true;

    /**
     * The service provider commands.
     */
    protected $commands = [
        'Trax\Sync\Console\Push',
    ];
    
    /**
     * The service provider publications.
     */
    protected $publications = [
        'trax-sync-config' => [
            'source' => 'publish/config',
            'destination' => 'config',
        ],
    ];


    /**
     * Register services.
     */
    protected function registerServices()
    {
        $plugin = $this->plugin;
        $this->app->singleton('Trax\Sync\SyncServices', function ($app) use ($plugin) {
            return new SyncServices($app, $plugin);
        });
    }


}
