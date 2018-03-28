<?php

namespace App\Console\Commands;

use App\Repositories\Caches\ChatRepository;
use Illuminate\Console\Command;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\Console\Socket\ChatSocket;

class ChatServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat_server:serve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '聊天伺服';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (shell_exec('lsof -i -P -n | grep :8080')) {
            echo 'Port 8080 busy!';
            return;
        }

        $chatRepository = new ChatRepository();
        $chatRepository->clearChatUsers();

        $this->info("Start Server");

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new ChatSocket()
                )
            ),
            8080
        );

        $server->run();
    }
}
