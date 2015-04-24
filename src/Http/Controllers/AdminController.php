<?php
namespace TypiCMS\Modules\Settings\Http\Controllers;

use Cache;
use Input;
use Log;
use Notification;
use Redirect;
use TypiCMS\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Settings\Repositories\SettingInterface;

class AdminController extends BaseAdminController
{

    public function __construct(SettingInterface $setting)
    {
        parent::__construct($setting);
    }

    /**
     * List models
     * GET /admin/model
     */
    public function index()
    {
        $data = $this->repository->all();
        return view('settings::admin.index')
            ->withData($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Redirect
     */
    public function store()
    {
        $data = Input::all();
        $this->repository->store($data);
        return Redirect::route('admin.settings.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Redirect
     */
    public function deleteImage()
    {
        $this->repository->deleteImage();
    }

    /**
     * Clear app cache
     *
     * @return Redirect
     */
    public function clearCache()
    {
        Cache::flush();
        Notification::success(trans('settings::global.Cache cleared') . '.');
        return Redirect::route('admin.settings.index');
    }
}
