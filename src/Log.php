<?php

namespace Lab42\Logger;

use DateTime;

class Log
{
    /**
     * @var int|null
     */
    private ?int $id = null;

    /**
     * @var string $message
     */
    private string $message;

    /**
     * @var int $level
     */
    private int $level;

    /**
     * @var array $context
     */
    private array $context;

    /**
     * @var DateTime $date
     */
    private DateTime $date;

    /**
     * @param string $message
     * @param int $level
     * @param array $context
     * @param DateTime $date
     */
    public function __construct(string $message, int $level, array $context, DateTime $date)
    {
        $this->message = $message;
        $this->level = $level;
        $this->context = $context;
        $this->date = $date;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $level
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * @param array $context
     */
    public function setContext(array $context): void
    {
        $this->context = $context;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }
}
