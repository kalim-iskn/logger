<?php

return [
    'host' => $_ENV['CLICKHOUSE_HOST'] ?? "clickhouse",
    'port' => $_ENV['CLICKHOUSE_PORT'] ?? "8123",
    'username' => $_ENV['CLICKHOUSE_USERNAME'] ?? "",
    'password' => $_ENV['CLICKHOUSE_PASSWORD'] ?? "",
    'log_table' => $_ENV['LOGGER_TABLE'] ?? "log"
];
