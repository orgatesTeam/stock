<?php

namespace App\Repositories\Caches;

class ChatRepository
{
    use AccessCache;

    const PREFIX_CHAT = 'chat:';

    const PREFIX_CHAT_USER = 'chat:user';

    //一個月秒數
    const SAVE_EXPIRE = 2592000;

    /**
     * @param int $userId
     */
    public function addChatRecord($msg)
    {
        $key = self::PREFIX_CHAT . today()->toDateString();
        $this->cache()->rPUSH($key, $msg);
    }

    /**
     * 取得當日聊天內容
     *
     * @return mixed
     */
    public function getChats($date)
    {
        $key = self::PREFIX_CHAT . $date;
        return $this->cache()->lRANGE($key, 0, -1);
    }

    /**
     * @param $connID
     * @param $userID
     */
    public function addChatUser($connID, $userID)
    {
        $key = self::PREFIX_CHAT_USER;

        $chatUsers = $this->getChatUsers();
        $chatUsers[$userID] = $connID;
        $this->cache()->hMSet($key, $chatUsers);
        $this->cache()->expire($key, self::SAVE_EXPIRE);
    }

    /**
     * @return mixed
     */
    public function getChatUsers()
    {
        $key = self::PREFIX_CHAT_USER;
        $this->cache()->expire($key, self::SAVE_EXPIRE);
        return $this->cache()->hGetAll($key);
    }

    /**
     * @param $userID
     *
     * @return bool
     */
    public function usExistChatUser($userID)
    {
        $users = $this->getChatUsers();
        return array_key_exists($userID, $users);
    }

    /**
     * 刪除單一登入使用者
     *
     * @param $connID
     */
    public function deleteChatUser($connID)
    {
        $chatUsers = $this->getChatUsers();

        if ($userID = array_search($connID, $chatUsers)) {
            $key = self::PREFIX_CHAT_USER;
            $this->cache()->hDEL($key, $userID);
            $this->cache()->expire($key, self::SAVE_EXPIRE);
        }
    }

    /**
     * 清空登入使用者
     */
    public function clearChatUsers()
    {
        $key = self::PREFIX_CHAT_USER;
        $this->cache()->del($key);
    }

    public function clearChats($data)
    {
        $key = self::PREFIX_CHAT.$data;
        $this->cache()->del($key);
    }
}
