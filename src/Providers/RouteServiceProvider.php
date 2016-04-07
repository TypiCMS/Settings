<?php

namespace TypiCMS\Modules\Settings\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

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
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function (Router $router) {
            /*
             * Admin routes
             */
            $router->get('admin/settings', 'AdminController@index')->name('admin::index-settings');
            $router->post('admin/settings', 'AdminController@store')->name('admin::store-setting');
            $router->get('admin/cache/clear', 'AdminController@clearCache')->name('admin::clear-cache');

            /*
             * API routes
             */
            $router->put('api/settings', 'AdminController@deleteImage');
        });
    }
}
