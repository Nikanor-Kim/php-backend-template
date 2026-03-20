<?php

namespace App\Utils;

class ResultBuilder
{
    private static function buildMessage(array $messages): string
    {
        return implode(PHP_EOL, $messages);
    }

    public static function success($payload)
    {
        return [
            'status' => 'success',
            'payload' => $payload
        ];
    }
    // Если есть примечание 
    public static function warning($notes, $payload)
    {
        return [
            'status' => 'warning',
            'notes' => self::buildMessage($notes),
            'payload' => $payload
        ];
    }
    // Если есть ошибка
    public static function error($notes, $error, $payload)
    {
        return [
            'status' => 'error',
            'notes' => self::buildMessage($notes),
            'error' => $error,
            'payload' => $payload
        ];
    }
}
