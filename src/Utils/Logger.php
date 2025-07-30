<?php
namespace App\Utils;

class Logger
{
    private const LEVELS = ['info', 'error', 'debug', 'warning', 'critical error'];
    private static $leadId = null;

    public static function setLeadId($leadId)
    {
        if (!self::$leadId) {
            self::$leadId = $leadId;
        }
    }

    private static function getLogFilePath($level = null): string
    {
        // Получаем базовый путь из .env
        $baseLogDir = $_ENV['LOG_PATH'] ?? __DIR__ . '/../../logs';

        // Подкаталог по уровню
        $subDir = ($level === 'debug') ? 'debug' : 'info';

        // Полный путь к папке
        $fullDirPath = rtrim($baseLogDir, '/') . '/' . $subDir;

        // Создаём папку, если не существует
        if (!is_dir($fullDirPath)) {
            mkdir($fullDirPath, 0775, true); // рекурсивное создание с правами
        }

        // Финальный путь до лог-файла
        $filename = date('Y-m-d') . '.log';

        return $fullDirPath . '/' . $filename;
    }

    public static function log($data, string $comment = '', $level = 'info', $callerFile = '')
    {
        $level = strtolower($level);
        if (!in_array($level, self::LEVELS)) {
            $level = 'info';
        }
        // $level = self::setEmoji($level);

        $timestamp = date('Y-m-d H:i:s');
        if ($callerFile == '') {
            $callerFile = basename(debug_backtrace()[0]['file'] ?? 'unknown');
        }


        $formattedData = is_bool($data)
            ? ($data ? 'true' : 'false')
            : (is_scalar($data)
                ? $data
                : var_export($data, true));

        $logMessage = "[$timestamp] "
            . "[" . self::setEmoji($level) . "] [$callerFile]"
            . (self::$leadId ? "[" . self::$leadId . "] " : "")
            . " $comment - $formattedData" . PHP_EOL;

        // Показываем в браузере или CLI, если включен режим отладки
        $isDebug = (php_sapi_name() === 'cli') || ($_GET['debug'] ?? false) || $_ENV['DEBUG'];

        if ($isDebug) {
            echo "<pre>$logMessage</pre>";
        }

        if ($level != 'debug') {
            file_put_contents(self::getLogFilePath(), $logMessage, FILE_APPEND);
        }
        file_put_contents(self::getLogFilePath('debug'), $logMessage, FILE_APPEND);

    }

    // Удобные алиасы
    public static function info($data, string $comment = '')
    {
        $callerFile = basename(debug_backtrace()[0]['file'] ?? 'unknown');
        self::log($data, $comment, 'info', $callerFile);
    }
    public static function warning($data, string $comment = '')
    {
        $callerFile = basename(debug_backtrace()[0]['file'] ?? 'unknown');
        self::log($data, $comment, 'warning', $callerFile);
    }

    public static function error($data, string $comment = '')
    {
        $callerFile = basename(debug_backtrace()[0]['file'] ?? 'unknown');
        self::log($data, $comment, 'error', $callerFile);
    }
    public static function criticalError($data, string $comment = '')
    {
        $callerFile = basename(debug_backtrace()[0]['file'] ?? 'unknown');
        self::log($data, $comment, 'critical error', $callerFile);
    }

    public static function debug($data, string $comment = '')
    {
        $callerFile = basename(debug_backtrace()[0]['file'] ?? 'unknown');
        self::log($data, $comment, 'debug', $callerFile);
    }

    private static function setEmoji($level)
    {
        switch ($level) {
            case 'info':
                return $level . ' ℹ️';

            case 'error':
                return $level . ' ❌';

            case 'debug':
                return $level . ' ♿️';

            case 'warning':
                return $level . ' ⚠️';

            case 'critical error':
                return $level . ' 🆘🆘🆘';

            default:
                return $level;
        }


    }
}
