<?php
namespace TypiCMS\Modules\Settings\Repositories;

use Illuminate\Database\Eloquent\Collection;
use TypiCMS\Services\Cache\CacheInterface;

class CacheDecorator implements SettingInterface
{

    public function __construct(SettingInterface $repo, CacheInterface $cache)
    {
        $this->repo = $repo;
        $this->cache = $cache;
    }

    /**
     * Get all settings
     *
     * @return stdClass
     */
    public function getAll()
    {
        $cacheKey = md5('Settings');

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $data = $this->repo->getAll($with);

        // Store in cache for next request
        $this->cache->put($cacheKey, $data);

        return $data;
    }

    /**
     * Update an existing model
     *
     * @param array  Data to update a model
     * @return boolean
     */
    public function store(array $data)
    {
        $this->cache->flush();
        return $this->repo->store($data);
    }

    /**
     * Build Settings Array
     *
     * @return array
     */
    public function getAllToArray()
    {
        $cacheKey = md5('SettingsToArray');

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $data = $this->repo->getAllToArray();

        // Store in cache for next request
        $this->cache->put($cacheKey, $data);

        return $data;
    }
}
