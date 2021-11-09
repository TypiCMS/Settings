<?php

namespace TypiCMS\Modules\Settings\Providers;

use Illuminate\Support\ServiceProvider;
use TypiCMS\Modules\Settings\Models\Setting;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        /*
         * Get configuration from DB and store it in the container
         */
        $TypiCMSConfig = $this->app->make('Settings')->allToArray();

        // merge config
        $config = $this->app['config']->get('typicms', []);
        $this->app['config']->set('typicms', array_merge($TypiCMSConfig, $config));

        $this->loadViewsFrom(__DIR__.'/../../resources/views/', 'settings');

        $this->publishes([
            __DIR__.'/../../database/migrations/create_settings_table.php.stub' => getMigrationFileName('create_settings_table'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/settings'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../../database/seeders/SettingsSeeder.php' => database_path('seeders/SettingsSeeder.php'),
        ], 'seeders');
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);

        $this->app->bind('Settings', Setting::class);
    }
}
