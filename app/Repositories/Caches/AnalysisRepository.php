<?php

namespace App\Repositories\Caches;

class AnalysisRepository
{
    use AccessCache;

    const PREFIX_ANALYSIS = 'analysis:short:';

    //一個月秒數
    const SAVE_EXPIRE = 2592000;

    /**
     * @param int $userId
     */
    public function saveShortAnalysis(int $userId, array $parameter)
    {
        $key = self::PREFIX_ANALYSIS . $userId;
        $this->cache()->hMSet($key, $parameter);
        $this->cache()->expire($key, self::SAVE_EXPIRE);
    }

    public function getBeforeShortAnalysis(int $userId)
    {
        $key = self::PREFIX_ANALYSIS . $userId;
        $this->cache()->expire($key, self::SAVE_EXPIRE);
        return $this->cache()->hGetAll($key);

    }
}
