<?php

namespace App\Console;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

trait ConsoleLogTrait
{
    public $loggerName = 'console';

    private $logger = '';

    public function logger($name)
    {
        if (!$this->logger || $this->loggerName != $name) {
            $this->loggerName = $name;
            $filePath = storage_path() . '/logs/console/' . $this->loggerName . today()->toDateString() . '.log';
            $this->logger = new Logger($this->loggerName);
            $this->logger->pushHandler(
                new StreamHandler($filePath),
                Logger::INFO);
        }

        return $this->logger;
    }
}