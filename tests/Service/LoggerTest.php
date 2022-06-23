<?php

namespace Service;

use ClickHouseDB\Client;
use Lab42\Logger\DBLogger;
use Lab42\Logger\LogLevelNumber;
use Lab42\Logger\Migration\CreateLogTable;
use Lab42\Logger\Repository\ClickHouseLogRepository;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class LoggerTest extends TestCase
{
    const LOG_TABLE = 'log_table_to_test';

    private ClickHouseLogRepository $repository;
    private Client $clickhouseClient;
    private array $testData;
    private DBLogger $logger;
    private array $levels = [
        LogLevelNumber::LEVEL_DEBUG => "debug",
        LogLevelNumber::LEVEL_INFO => "info",
        LogLevelNumber::LEVEL_NOTICE => "notice",
        LogLevelNumber::LEVEL_WARNING => "warning",
        LogLevelNumber::LEVEL_ERROR => "error",
        LogLevelNumber::LEVEL_CRITICAL => "critical",
        LogLevelNumber::LEVEL_ALERT => "alert",
        LogLevelNumber::LEVEL_EMERGENCY => "emergency"
    ];

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $logMigration = new CreateLogTable(self::LOG_TABLE);
        $this->clickhouseClient = new Client(require 'config/clickhouse.php');
        $this->clickhouseClient->write($logMigration->getSql());

        $_ENV['LOGGER_TABLE'] = self::LOG_TABLE;
        $this->repository = new ClickHouseLogRepository();
        $this->testData = [
            "message" => "Message",
            "context" => ["text" => ["qwerty" => 1], "qeety"]
        ];
        $this->logger = new DBLogger();
    }

    public function testSaveLevelsMethods()
    {
        $this->truncateTable();

        foreach ($this->levels as $method) {
            $reflectionMethod = new ReflectionMethod('\Lab42\Logger\DBLogger', $method);
            $reflectionMethod->invoke($this->logger, $this->testData['message'], $this->testData['context']);
        }

        $this->checkAllByLevels($this->repository->get());
    }

    public function testSaveLogMethod()
    {
        $this->truncateTable();

        foreach ($this->levels as $level => $method) {
            $this->logger->log($level, $this->testData['message'], $this->testData['context']);
        }

        $this->checkAllByLevels($this->repository->get());
    }

    private function checkAllByLevels(array $logsResult)
    {
        $levelsCount = count($this->levels);
        $this->assertCount($levelsCount, $logsResult);

        foreach ($this->levels as $level => $method) {
            $log = $logsResult[$levelsCount - $level - 1];
            $this->assertEquals($level, $log->getLevel());
            $this->assertEquals($this->testData['message'], $log->getMessage());
            $this->assertEquals($this->testData['context'], $log->getContext());
        }
    }

    private function truncateTable()
    {
        $this->clickhouseClient->write("TRUNCATE TABLE " . self::LOG_TABLE);
    }
}
