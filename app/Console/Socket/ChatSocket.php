<?php
namespace App\Console\Socket;

use Illuminate\Support\Carbon;
use Ratchet\ConnectionInterface;
use App\Console\Socket\Base\BaseSocket;
use App\Repositories\Caches\ChatRepository;

class ChatSocket extends BaseSocket
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $everyoneSend = true;

        $msgObj = json_decode($msg);
        $msgObj->datetime = now(config('app.current_timezone'))->toDateTimeString();
        $chat = new ChatRepository();
        $chat->addChatRecord(json_encode($msgObj));

        $numRecv = count($this->clients) - 1;

        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msgObj->content, $numRecv, $numRecv == 1 ? '' : 's');
        foreach ($this->clients as $client) {
            if ($from !== $client || $everyoneSend) {
                // The sender is not the receiver, send to each client connected
                $client->send(
                    json_encode(
                        $chat->getChat()
                    ));
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}