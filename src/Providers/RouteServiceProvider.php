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
            $router->get('admin/settings', ['as' => 'admin.settings.index', 'uses' => 'AdminController@index']);
            $router->post('admin/settings', ['as' => 'admin.settings.store', 'uses' => 'AdminController@store']);
            $router->get('admin/cache/clear', ['as' => 'cache.clear', 'uses' => 'AdminController@clearCache']);

            /*
             * API routes
             */
            $router->put('api/settings', 'AdminController@deleteImage');
        });
    }
}
