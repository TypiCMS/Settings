<?php

namespace TypiCMS\Modules\Settings\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Krucas\Notification\Facades\Notification;
use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Settings\Repositories\SettingInterface;

class AdminController extends BaseAdminController
{
    public function __construct(SettingInterface $setting)
    {
        parent::__construct($setting);
    }

    /**
     * List models.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $data = $this->repository->all();

        return view('settings::admin.index')
            ->with(compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $data = Request::all();
        $this->repository->store($data);

        return redirect()->route('admin.settings.index');
    }

    /**
     * Delete image.
     *
     * @return null
     */
    public function deleteImage()
    {
        $this->repository->deleteImage();
    }

    /**
     * Clear app cache.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearCache()
    {
        Cache::flush();
        Notification::success(trans('settings::global.Cache cleared').'.');

        return redirect()->route('admin.settings.index');
    }
}
