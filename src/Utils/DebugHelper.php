<?php

namespace App\Utils;

class DebugHelper
{

    public static function save(array $data): void
    {
        $file = $_ENV['DEBUG_WEBHOOK_FILE'];
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($file, $json);
        Logger::debug("Debug файл сохранен");
    }

    public static function load(): array
    {
        $file = $_ENV['DEBUG_WEBHOOK_FILE'];

        if (!file_exists($file)) {
            Logger::debug("Debug файл не загружен");

            return [];
        }

        $json = file_get_contents($file);
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Logger::log("Ошибка при разборе JSON: " . json_last_error_msg());
            return [];
        }
        Logger::debug("Debug файл загружен");


        return $data;
    }
}
