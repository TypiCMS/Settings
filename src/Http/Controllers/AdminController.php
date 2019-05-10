<?php

namespace TypiCMS\Modules\Settings\Http\Controllers;

use Croppa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Core\Services\FileUploader;
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
    public function save(Request $request, FileUploader $fileUploader)
    {
        $data = $request->except('_token');

        if ($request->hasFile('image')) {
            $file = $fileUploader->handle($request->file('image'), 'settings');
            $this->deleteImage();
            $data['image'] = $file['filename'];
        }

        foreach ($data as $group_name => $array) {
            if (!is_array($array)) {
                $array = [$group_name => $array];
                $group_name = 'config';
            }
            foreach ($array as $key_name => $value) {
                $model = Setting::firstOrCreate(['key_name' => $key_name, 'group_name' => $group_name]);
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
        if ($filename = Setting::where('key_name', 'image')->value('value')) {
            try {
                Croppa::delete('storage/settings/'.$filename);
            } catch (Exception $e) {
                Log::info($e->getMessage());
            }
        }
        Setting::where('key_name', 'image')->delete();
        $this->repository->forgetCache();
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
