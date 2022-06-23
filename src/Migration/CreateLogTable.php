<?php

namespace Lab42\Logger\Migration;

class CreateLogTable
{
    private string $tableName;

    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
    }

    public function getSql(): string
    {
        return "CREATE TABLE IF NOT EXISTS $this->tableName\n" .
            "(\n" .
            "    date DateTime CODEC(LZ4HC),\n" .
            "    message String,\n" .
            "    level Int8 DEFAULT 1,\n" .
            "    context String\n" .
            ") ENGINE=MergeTree()\n" .
            "ORDER BY date\n" .
            "PRIMARY KEY date";
    }
}
