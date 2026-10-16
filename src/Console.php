<?php

namespace TaskManager;

class Console
{
    public const RED = "\033[31m";
    public const GREEN = "\033[32m";
    public const YELLOW = "\033[33m";
    public const BLUE = "\033[34m";
    public const CYAN = "\033[36m";
    public const GRAY = "\033[90m";
    public const WHITE = "\033[37m";
    public const RESET = "\033[0m";

    public static function write(string $message, string $color = self::RESET): void
    {
        echo $color . $message . self::RESET . PHP_EOL;
    }

    public static function success(string $message): void
    {
        self::write($message, self::GREEN);
    }

    public static function error(string $message): void
    {
        self::write($message, self::RED);
    }

    public static function warning(string $message): void
    {
        self::write($message, self::YELLOW);
    }

    public static function title(string $message): void
    {
        self::write($message, self::CYAN);
    }

    public static function info(string $message): void
    {
        self::write($message, self::BLUE);
    }
}
