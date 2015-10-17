<?php

namespace TypiCMS\Modules\Settings\Repositories;

use stdClass;

interface SettingInterface
{
    /**
     * Get all settings.
     *
     * @return stdClass
     */
    public function all();

    /**
     * Update an existing item.
     *
     * @param array  Data to update an item
     *
     * @return bool
     */
    public function store(array $data);

    /**
     * Delete image.
     *
     * @return void
     */
    public function deleteImage();

    /**
     * Build Settings Array.
     *
     * @return array
     */
    public function allToArray();
}
