<?php

namespace Lab42\Logger;

use DateTime;
use Lab42\Logger\Repository\ClickHouseLogRepository;
use Psr\Log\LoggerInterface;

class DBLogger implements LoggerInterface
{
    private ClickHouseLogRepository $loggerRepo;

    public function __construct()
    {
        $this->loggerRepo = new ClickHouseLogRepository();
    }

    public function emergency($message, array $context = array())
    {
        $this->saveLog(LogLevelNumber::LEVEL_EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = array())
    {
        $this->saveLog(LogLevelNumber::LEVEL_ALERT, $message, $context);
    }

    public function critical($message, array $context = array())
    {
        $this->saveLog(LogLevelNumber::LEVEL_CRITICAL, $message, $context);
    }

    public function error($message, array $context = array())
    {
        $this->saveLog(LogLevelNumber::LEVEL_ERROR, $message, $context);
    }

    public function warning($message, array $context = array())
    {
        $this->saveLog(LogLevelNumber::LEVEL_WARNING, $message, $context);
    }

    public function notice($message, array $context = array())
    {
        $this->saveLog(LogLevelNumber::LEVEL_NOTICE, $message, $context);
    }

    public function info($message, array $context = array())
    {
        $this->saveLog(LogLevelNumber::LEVEL_INFO, $message, $context);
    }

    public function debug($message, array $context = array())
    {
        $this->saveLog(LogLevelNumber::LEVEL_DEBUG, $message, $context);
    }

    public function log($level, $message, array $context = array())
    {
        $this->saveLog($level, $message, $context);
    }

    private function saveLog(int $level, string $message, array $context): void
    {
        $log = new Log($message, $level, $context, new DateTime());
        $this->loggerRepo->save($log);
    }
}
