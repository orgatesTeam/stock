<?php
namespace App\Console\Socket;

use Illuminate\Support\Carbon;
use Ratchet\ConnectionInterface;
use App\Console\Socket\Base\BaseSocket;
use App\Repositories\Caches\ChatRepository;

class ChatSocket extends BaseSocket
{
    protected $clients;
    protected $chatRepository;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->chatRepository = new ChatRepository();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        if (!$this->isMySource($conn)) {
            return $conn->close();
        }

        $this->clients->attach($conn);

        $this->sendUsers();
        echo "New connection! ({$conn->resourceId})\n";
        var_dump($this->chatRepository->getChatUsers());
    }

    protected function isMySource($conn)
    {
        return $conn->httpRequest->getHeaders()['Origin'][0] == config('webSocket.fromUrl');
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $msgObj = json_decode($msg);

        if (isset($msgObj->firstLogin)) {
            $this->doFirstLogin($from, $msgObj);
            return;
        }

        $msgObj->datetime = now(config('app.current_timezone'))->toDateTimeString();

        $this->chatRepository->addChatRecord(json_encode($msgObj));
        $this->chatRepository->addChatUser($from->resourceId, $msgObj->userID);
        $numRecv = count($this->clients) - 1;

        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n", $from->resourceId, $msgObj->content, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            if ($from !== $client || true) {
                $client->send($this->jsonMessageChats());
            }
        }
    }

    protected function doFirstLogin($from, $msgObj)
    {
        //重複登入
        if ($this->chatRepository->usExistChatUser($msgObj->userID)) {
            $from->send(json_encode(['alert' => '請勿重複登入']));
            return $from->close();
        }

        $this->chatRepository->addChatUser($from->resourceId, $msgObj->userID);
        $this->sendUsers();
        $from->send($this->jsonMessageChats());
        return;
    }

    protected function jsonMessageChats()
    {
        return json_encode(['message' => $this->chatRepository->getChat()]);
    }

    protected function sendUsers()
    {
        foreach ($this->clients as $client) {

            $client->send(json_encode(['users' => array_keys($this->chatRepository->getChatUsers())]));
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        $this->chatRepository->deleteChatUser($conn->resourceId);
        echo "Connection {$conn->resourceId} has disconnected\n";
        $this->sendUsers();
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}