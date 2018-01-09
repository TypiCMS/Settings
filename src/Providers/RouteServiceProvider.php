<?php

namespace TypiCMS\Modules\Settings\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'TypiCMS\Modules\Settings\Http\Controllers';

    /**
     * Define the routes for the application.
     *
     * @return null
     */
    public function map()
    {
        Route::namespace($this->namespace)->group(function (Router $router) {
            /*
             * Admin routes
             */
            $router->middleware('admin')->prefix('admin')->group(function (Router $router) {
                $router->get('settings', 'AdminController@index')->name('admin::index-settings')->middleware('can:see-settings');
                $router->post('settings', 'AdminController@save')->name('admin::update-settings')->middleware('can:update-setting');
                $router->get('cache/clear', 'AdminController@clearCache')->name('admin::clear-cache')->middleware('can:clear-cache');
                $router->patch('settings', 'AdminController@deleteImage')->middleware('can:update-setting');
            });
        });
    }
}
