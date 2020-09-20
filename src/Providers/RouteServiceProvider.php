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
        /*
         * Admin routes
         */
        Route::middleware('admin')->prefix('admin')->name('admin::')->group(function (Router $router) {
            $router->get('settings', [AdminController::class, 'index'])->name('index-settings')->middleware('can:read settings');
            $router->post('settings', [AdminController::class, 'save'])->name('update-settings')->middleware('can:update settings');
            $router->get('cache/clear', [AdminController::class, 'clearCache'])->name('clear-cache')->middleware('can:clear cache');
            $router->patch('settings', [AdminController::class, 'deleteImage'])->middleware('can:update settings');
        });
    }
}
