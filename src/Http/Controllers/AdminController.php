<?php

namespace TypiCMS\Modules\Settings\Http\Controllers;

use Croppa;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Krucas\Notification\Facades\Notification;
use TypiCMS\Modules\Core\Facades\FileUpload;
use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Settings\Repositories\EloquentSetting;

class AdminController extends BaseAdminController
{
    public function __construct(EloquentSetting $setting)
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

        if ($data['image'] == 'delete') {
            $data['image'] = null;
        }

        if (Request::hasFile('image')) {
            $file = FileUpload::handle(Request::file('image'), 'uploads/settings');
            $data['image'] = $file['filename'];
        }

        foreach ($data as $group_name => $array) {
            if (!is_array($array)) {
                $array = [$group_name => $array];
                $group_name = 'config';
            }
            foreach ($array as $key_name => $value) {
                $model = $this->where('key_name', $key_name)->where('group_name', $group_name)->first();
                $model = $model ? $model : new $this->model();
                $model->group_name = $group_name;
                $model->key_name = $key_name;
                $model->value = $value;
                $model->save();
            }
        }

        return redirect()->route('admin::index-settings');
    }

    /**
     * Delete image.
     *
     * @return null
     */
    public function deleteImage()
    {
        $row = $this->where('key_name', 'image')->first();
        $filedir = '/uploads/settings/';
        $filename = $row->value;
        $row->value = null;
        $row->save();
        try {
            Croppa::delete($filedir.$filename);
            File::delete(public_path().$filedir.$filename);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
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

        return redirect()->route('admin::index-settings');
    }
}
