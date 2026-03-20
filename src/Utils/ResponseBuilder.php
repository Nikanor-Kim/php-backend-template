<?php

namespace App\Utils;

use App\Utils\Timer;


class ResponseBuilder
{
    private const STATUSES = ['success', 'error', 'warning'];
    public static function success($message, $data = null, $code = 200): void
    {
        http_response_code($code);
        self::send($message, 'success', $data);
        Timer::stopTimer();
        exit;
    }

    public static function error($message, $data = null, $code = 500): void
    {
        http_response_code($code);
        self::send($message, 'error', $data);
        Timer::stopTimer();
        exit;
    }

    public static function warning($message, $data = null, $code = 400): void
    {
        http_response_code($code);
        self::send($message, 'warning', $data);
        Timer::stopTimer();
        exit;
    }

    private static function send($message, $status = 'success', $data = null): void
    {
        $status = strtolower($status);
        if (!in_array($status, self::STATUSES)) {
            $status = 'undefined';
        }
        $response = [
            'status' => $status,
            'message' => $message,
        ];

        if ($data) {
            $response['data'] = $data;
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
}
