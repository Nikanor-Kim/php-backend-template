<?php
namespace App\Utils;

class ResultBuilder
{
    public static function success($message)
    {
        return ['status' => 'success', 'message' => $message];
    }
    public static function error($message)
    {
        return ['status' => 'error', 'message' => $message];
    }
    public static function warning($message)
    {
        return ['status' => 'warning', 'message' => $message];
    }
}