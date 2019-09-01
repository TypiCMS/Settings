<?php

namespace TypiCMS\Modules\Settings\Models;

use Eloquent;
use Exception;

class Setting extends Eloquent
{
    protected $fillable = [
        'group_name',
        'key_name',
        'value',
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'settings';

    public function allToArray()
    {
        $config = [];

        try {
            foreach ($this->get() as $object) {
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
