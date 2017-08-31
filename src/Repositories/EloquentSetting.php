<?php

namespace TypiCMS\Modules\Settings\Repositories;

use Exception;
use Illuminate\Support\Facades\Log;
use stdClass;
use TypiCMS\Modules\Core\Repositories\EloquentRepository;
use TypiCMS\Modules\Settings\Models\Setting;

class EloquentSetting extends EloquentRepository
{
    protected $repositoryId = 'settings';

    protected $model = Setting::class;

    /**
     * Get all settings.
     *
     * @return stdClass
     */
    public function all()
    {
        $data = new stdClass();
        foreach ($this->findAll() as $model) {
            $value = is_numeric($model->value) ? (int) $model->value : $model->value;
            $group_name = $model->group_name;
            $key_name = $model->key_name;
            if ($group_name != 'config') {
                if (!isset($data->$group_name)) {
                    $data->$group_name = new stdClass();
                }
                $data->$group_name->$key_name = $value;
            } else {
                $data->$key_name = $value;
            }
        }

        return $data;
    }

    /**
     * Build Settings Array.
     *
     * @return array
     */
    public function allToArray()
    {
        $config = [];
        try {
            foreach ($this->findAll() as $object) {
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
