<?php

namespace TypiCMS\Modules\Settings\Http\Controllers;

use Croppa;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use TypiCMS\Modules\Core\Facades\FileUpload;
use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Settings\Models\Setting;
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
     * Save settings.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save()
    {
        $data = Request::except('_token');

        if (Request::hasFile('image')) {
            $file = FileUpload::handle(Request::file('image'), 'settings');
            $this->deleteImage();
            $data['image'] = $file['filename'];
        }

        foreach ($data as $group_name => $array) {
            if (!is_array($array)) {
                $array = [$group_name => $array];
                $group_name = 'config';
            }
            foreach ($array as $key_name => $value) {
                $model = Setting::where('key_name', $key_name)->where('group_name', $group_name)->first();
                $model = $model ?: new Setting;
                $model->group_name = $group_name;
                $model->key_name = $key_name;
                $model->value = $value;
                $model->save();
                $this->repository->forgetCache();
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
        $row = Setting::where('key_name', 'image')->first();
        $filename = $row->value;
        $row->value = null;
        $row->save();
        $this->repository->forgetCache();
        try {
            Croppa::delete('uploads/settings/'.$filename);
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
        $message = __('Cache cleared.');

        return redirect()->route('admin::index-settings')
            ->with(compact('message'));
    }
}
