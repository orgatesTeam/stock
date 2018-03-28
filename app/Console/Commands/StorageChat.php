<?php

namespace App\Console\Commands;

use App\Chat;
use App\Enums\ChatType;
use App\Repositories\Caches\ChatRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class StorageChat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:chat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '將 Redis Chat 存入 Database Table Chats ';

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
        $lastDate = today()->addDay(-1)->toDateString();
        $cacheRepository = app(ChatRepository::class);
        $chats = $cacheRepository->getChats($lastDate);

        if (!$chats) {
            echo 'That storage no exits date:' . $lastDate;
            return;
        }

        Chat::create([
            'type'    => ChatType::HALL,
            'date'    => $lastDate,
            'content' => json_encode($chats)
        ]);

        $cacheRepository->clearChats($lastDate);
        echo 'have storage';
    }
}
