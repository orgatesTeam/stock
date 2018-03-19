<?php

namespace App\Repositories\Caches;

class ChatRepository
{
    use AccessCache;

    const PREFIX_CHAT = 'chat:';

    const PREFIX_CHAT_USER = 'chat:user:';

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

    public function addChatUser($connID, $userID)
    {
        $key = self::PREFIX_CHAT_USER;

        $chatUsers = $this->getChatUsers();
        $chatUsers[$userID] = $connID;
        $this->cache()->hMSet($key, $chatUsers);
        $this->cache()->expire($key, self::SAVE_EXPIRE);
    }

    public function getChatUsers()
    {
        $key = self::PREFIX_CHAT_USER;
        $this->cache()->expire($key, self::SAVE_EXPIRE);
        return $this->cache()->hGetAll($key);
    }

    public function usExistChatUser($userID)
    {
        $users = $this->getChatUsers();
        return array_key_exists($userID, $users);
    }

    public function deleteChatUser($connID)
    {
        $chatUsers = $this->getChatUsers();

        if ($userID = array_search($connID, $chatUsers)) {
            $key = self::PREFIX_CHAT_USER;
            $this->cache()->hDEL($key, $userID);
            $this->cache()->expire($key, self::SAVE_EXPIRE);
        }
    }
}
