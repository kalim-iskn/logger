<?php

namespace Lab42\Logger\Repository;

use ClickHouseDB\Client;
use DateTime;
use Exception;
use Lab42\Logger\Exception\IncorrectLimitException;
use Lab42\Logger\Log;

class ClickHouseLogRepository
{
    private Client $client;
    private string $logTable;

    public function __construct()
    {
        $config = require 'config/clickhouse.php';
        $this->client = new Client($config);
        $this->logTable = $config['log_table'];
    }

    /**
     * @param Log $log
     */
    public function save(Log $log): void
    {
        $this->client->insert(
            $this->logTable,
            [
                [
                    "date" => $log->getDate(),
                    "level" => $log->getLevel(),
                    "message" => $log->getMessage(),
                    "context" => json_encode($log->getContext())
                ]
            ],
            ["date", "level", "message", "context"]
        );
    }

    /**
     * @param int $limit
     * @return Log[]
     * @throws IncorrectLimitException
     * @throws Exception
     */
    public function get(int $limit = 50): array
    {
        if ($limit > 200 || $limit < 1) {
            throw new IncorrectLimitException();
        }

        $statement = $this->client->select("SELECT * FROM $this->logTable ORDER BY date DESC LIMIT $limit");

        return $this->convertToDtoCollection($statement->rows());
    }

    /**
     * @param array $result
     * @return Log[]
     * @throws Exception
     */
    private function convertToDtoCollection(array $result): array
    {
        $collection = [];
        foreach ($result as $logArray) {
            $collection[] = new Log(
                $logArray['message'],
                $logArray['level'],
                json_decode($logArray['context'], true),
                new DateTime($logArray['date'])
            );
        }

        return $collection;
    }
}
