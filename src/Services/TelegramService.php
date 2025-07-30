<?php
namespace App\Services;

use App\Utils\Logger;

class TelegramService
{
    private static function sendMessage(string $message): void
    {
        $url = "https://api.telegram.org/bot{$_ENV['TG_TOKEN']}/sendMessage";

        $data = [
            'chat_id' => $_ENV['TG_CHAT_ID'],                   // Например: -1001234567890
            'message_thread_id' => $_ENV['TG_THREAD_ID'],       // ID топика (обсуждения)
            'text' => $message,
            // 'parse_mode' => 'Markdown', // или 'HTML'
        ];
        // Logger::debug($data, 'TG data');

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_POSTFIELDS => http_build_query($data),
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            Logger::error('Telegram CURL error: ' . curl_error($ch));

        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode !== 200) {
            Logger::error("Telegram error response [$httpCode]: $response");
        }

        curl_close($ch);
    }

    public static function success(string $text)
    {
        $message = "SUCCESS ✅: " . $text;
        self::sendMessage($message);
    }

    public static function warning(string $text)
    {
        $message = "WARNING ⚠️: " . $text;
        self::sendMessage($message);
    }
    public static function error(string $text)
    {
        $message = "ERROR ❌: " . $text;
        self::sendMessage($message);
    }
    public static function criticalError(string $text)
    {
        $message = " CRITICAL ERROR 🆘🆘🆘: " . $text;
        self::sendMessage($message);
    }
}