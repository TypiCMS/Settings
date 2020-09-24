<?php

namespace TypiCMS\Modules\Settings\Providers;

use Illuminate\Support\ServiceProvider;
use TypiCMS\Modules\Settings\Models\Setting;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        /*
         * Get configuration from DB and store it in the container
         */
        $TypiCMSConfig = $this->app->make('Settings')->allToArray();

        // merge config
        $config = $this->app['config']->get('typicms', []);
        $this->app['config']->set('typicms', array_merge($TypiCMSConfig, $config));

        $this->loadViewsFrom(__DIR__.'/../resources/views/', 'settings');

        $this->publishes([
            __DIR__.'/../database/migrations/create_settings_table.php.stub' => getMigrationFileName('create_settings_table'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/settings'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../database/seeds/SettingsSeeder.php' => database_path('seeds/SettingsSeeder.php'),
        ], 'seeders');
    }

    public function register()
    {
        $app = $this->app;

        /*
         * Register route service provider
         */
        $app->register(RouteServiceProvider::class);

        $app->bind('Settings', Setting::class);
    }
}