<?php
namespace TypiCMS\Modules\Settings\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider {

    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'TypiCMS\Modules\Settings\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);

    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function(Router $router) {
            /**
             * Admin routes
             */
            $router->resource('admin/settings', 'AdminController', ['only' => ['index', 'store']]);
            $router->put('api/settings', 'AdminController@deleteImage');
            $router->get('admin/cache/clear', array('as' => 'cache.clear', 'uses' => 'AdminController@clearCache'));
        });
    }

}
