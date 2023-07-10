<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class CacheHelper
{
    private string $store = 'file';
    private int $ttl = 300;

    public function __construct(string $store)
    {
        $this->store = $store;
    }

    public function setCached($key, $value)
    {
        $cachedData = Cache::store($this->store)->put($key, $value);

        return json_decode($cachedData);
    }

    public function getCached($key)
    {
        $cachedData = Cache::store($this->store)->get($key);

        return json_decode($cachedData);
    }

    public function removeCached($key)
    {
        Cache::store($this->store)->forget($key);
    }

}
