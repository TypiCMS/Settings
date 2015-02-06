<?php
namespace TypiCMS\Modules\Settings\Repositories;

use stdClass;

interface SettingInterface
{

    /**
     * Get all settings
     *
     * @return stdClass
     */
    public function getAll();

    /**
     * Update an existing item
     *
     * @param array  Data to update an item
     * @return boolean
     */
    public function store(array $data);

    /**
     * Build Settings Array
     *
     * @return array
     */
    public function getAllToArray();
}
