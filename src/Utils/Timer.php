<?php

namespace App\Utils;

class Timer
{
    public static $startTime;
    public static $endTime;

    public static $time;

    public static function startTimer()
    {
        self::$startTime = microtime(true);
    }
    public static function stopTimer()
    {
        self::$endTime = microtime(true);
        self::$time = self::$endTime - self::$startTime;
        self::$time = number_format(self::$time, 6) . " сек";
        Logger::debug(self::$time, 'Время выполнения: ');
    }
}
