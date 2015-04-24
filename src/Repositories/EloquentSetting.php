<?php
namespace TypiCMS\Modules\Settings\Repositories;

use Croppa;
use DB;
use Exception;
use File;
use FileUpload;
use Illuminate\Database\Eloquent\Model;
use Input;
use Log;
use stdClass;

class EloquentSetting implements SettingInterface
{

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all settings
     *
     * @return stdClass
     */
    public function all()
    {
        $data = new stdClass;
        foreach ($this->model->get() as $model) {
            $value = is_numeric($model->value) ? (int) $model->value : $model->value ;
            $group_name = $model->group_name;
            $key_name = $model->key_name;
            if ($group_name != 'config') {
                if (! isset($data->$group_name)) {
                    $data->$group_name = new stdClass;
                }
                $data->$group_name->$key_name = $value;
            } else {
                $data->$key_name = $value;
            }
        }

        return $data;
    }

    /**
     * Update an existing model
     *
     * @param array Data to update a model
     * @return boolean
     */
    public function store(array $data)
    {

        $data = array_except($data, array('_method', '_token', 'exit'));

        if ($data['image'] == 'delete') {
            $data['image'] = null;
        }

        if (Input::hasFile('image')) {
            $file = FileUpload::handle(Input::file('image'), 'uploads/settings');
            $data['image'] = $file['filename'];
        }

        foreach ($data as $group_name => $array) {
            if (! is_array($array)) {
                $array = array($group_name => $array);
                $group_name = 'config';
            }
            foreach ($array as $key_name => $value) {
                $model = $this->model->where('key_name', $key_name)->where('group_name', $group_name)->first();
                $model = $model ? $model : new $this->model ;
                $model->group_name = $group_name;
                $model->key_name = $key_name;
                $model->value = $value;
                $model->save();
            }
        }

        return true;

    }

    /**
     * Delete image
     *
     * @return void
     */
    public function deleteImage()
    {
        $row = $this->model->where('key_name', 'image')->first();
        $filedir = '/uploads/settings/';
        $filename = $row->value;
        $row->value = null;
        $row->save();
        try {
            Croppa::delete($filedir . $filename);
            File::delete(public_path() . $filedir . $filename);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Build Settings Array
     *
     * @return array
     */
    public function allToArray()
    {
        $config = [];
        try {
            foreach (DB::table('settings')->get() as $object) {
                $key = $object->key_name;
                if ($object->group_name != 'config') {
                    $config[$object->group_name][$key] = $object->value;
                } else {
                    $config[$key] = $object->value;
                }
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return $config;
    }
}
