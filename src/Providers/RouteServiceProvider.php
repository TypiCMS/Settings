<?php

namespace TypiCMS\Modules\Settings\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use TypiCMS\Modules\Settings\Http\Controllers\AdminController;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define the routes for the application.
     */
    public function map()
    {
        Route::namespace($this->namespace)->group(function (Router $router) {
            /*
             * Admin routes
             */
            $router->middleware('admin')->prefix('admin')->group(function (Router $router) {
                $router->get('settings', [AdminController::class, 'index'])->name('admin::index-settings')->middleware('can:read settings');
                $router->post('settings', [AdminController::class, 'save'])->name('admin::update-settings')->middleware('can:update settings');
                $router->get('cache/clear', [AdminController::class, 'clearCache'])->name('admin::clear-cache')->middleware('can:clear cache');
                $router->patch('settings', [AdminController::class, 'deleteImage'])->middleware('can:update settings');
            });
        });
    }
}
