<?php

namespace Utils;

final class Logger
{
    public static function error(string $message, array $context = []): void
    {
        $logDir = __DIR__ . '/../../logs';

        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $line = sprintf(
            "[%s] ERROR %s %s\n",
            date('Y-m-d H:i:s'),
            $message,
            $context ? json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : ''
        );

        error_log($line, 3, $logDir . '/app.log');
    }
}