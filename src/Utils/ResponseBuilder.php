<?php
namespace App\Utils;

class ResponseBuilder
{
    private const STATUSES = ['success', 'error', 'debug', 'warning'];
    public static function success($message, $code = 200): void
    {
        http_response_code($code);
        self::send($message);
        self::finalizeOutput();

    }

    public static function error($message, $code = 500): void
    {
        http_response_code($code);
        self::send($message, 'error');
        exit;
    }

    public static function warning($message, $code = 400): void
    {
        http_response_code($code);
        self::send($message, 'warning');
        exit;
    }

    private static function send($message, $status = 'success'): void
    {
        $status = strtolower($status);
        if (!in_array($status, self::STATUSES)) {
            $status = 'undefined';
        }
        // header('Content-Type: application/json; charset=utf-8');
        $data = [
            'status' => $status,
            'message' => $message
        ];
        echo json_encode($data, JSON_UNESCAPED_UNICODE);


    }
    private static function finalizeOutput()
    {
        // Отдаём всё клиенту и разрываем соединение
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        } else {
            // На случай обычного apache без fastcgi
            if (ob_get_level() > 0) {
                ob_end_flush();
            }
            flush();
        }
    }
}
