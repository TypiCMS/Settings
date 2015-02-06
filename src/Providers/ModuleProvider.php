<?php
namespace TypiCMS\Modules\Settings\Providers;

use Config;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Lang;
use Settings;
use TypiCMS\Modules\Settings\Models\Setting;
use TypiCMS\Modules\Settings\Repositories\CacheDecorator;
use TypiCMS\Modules\Settings\Repositories\EloquentSetting;
use TypiCMS\Services\Cache\LaravelCache;
use View;

class ModuleProvider extends ServiceProvider
{

    public function boot()
    {
        /**
         * Get configuration from DB and store it in the container
         */
        $TypiCMSConfig = app('TypiCMS\Modules\Settings\Repositories\SettingInterface')
            ->getAllToArray();
        Config::set('typicms', $TypiCMSConfig);

        // Add dirs
        View::addNamespace('settings', __DIR__ . '/../views/');
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'settings');
        $this->publishes([
            __DIR__ . '/../config/' => config_path('typicms/settings'),
        ], 'config');
        $this->publishes([
            __DIR__ . '/../migrations/' => base_path('/database/migrations'),
        ], 'migrations');
    }

    public function register()
    {

        $app = $this->app;

        /**
         * Register route service provider
         */
        $app->register('TypiCMS\Modules\Settings\Providers\RouteServiceProvider');

        $app->bind('TypiCMS\Modules\Settings\Repositories\SettingInterface', function (Application $app) {
            $repository = new EloquentSetting(new Setting);
            if (! Config::get('app.cache')) {
                return $repository;
            }
            $laravelCache = new LaravelCache($app['cache'], 'settings', 10);

            return new CacheDecorator($repository, $laravelCache);
        });

    }
}
