<?php
namespace App\Utils;

use App\Utils\ResponseBuilder;
use App\Utils\Logger;

class RequestValidator
{
    public static function validateInput($inputData)
    {
        if (empty($inputData)) {
            Logger::error('Пустой input');
            ResponseBuilder::error('Пустой input');
        }

        if (!isset($inputData['leads']['sensei'])){
            Logger::error('Невалидный input');
            ResponseBuilder::error('Невалидный input');
        }
    }
}
