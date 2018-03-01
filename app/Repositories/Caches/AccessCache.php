<?php

namespace App\Repositories\Caches;

use Illuminate\Support\Facades\Redis;

trait AccessCache
{
    protected $redis;

    /**
     * @return Redis
     */
    public function cache()
    {
        if (!$this->redis) {
            $this->redis = Redis::connection();
        }

        return $this->redis;
    }

    /**
     * @param $prefix
     * @param $key
     * @return string
     */
    protected function getCacheKey($prefix, $key)
    {
        if (is_array($key)) {
            $key = implode(',', $key);
        }

        return $prefix . $key;
    }
}
