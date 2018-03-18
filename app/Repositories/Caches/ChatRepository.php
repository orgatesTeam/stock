<?php

namespace App\Repositories\Caches;

class ChatRepository
{
    use AccessCache;

    const PREFIX_CHAT = 'chat:';

    //一個月秒數
    const SAVE_EXPIRE = 2592000;

    /**
     * @param int $userId
     */
    public function addChatRecord($msg)
    {
        $key = self::PREFIX_CHAT . today()->toDateTimeString();
        $this->cache()->rPUSH($key, $msg);
    }

    public function getChat()
    {
        $key = self::PREFIX_CHAT . today()->toDateTimeString();
        return $this->cache()->lRANGE($key, 0, -1);
    }
}
