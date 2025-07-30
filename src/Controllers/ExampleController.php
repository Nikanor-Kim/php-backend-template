<?php

namespace App\Controllers;

use App\Utils\Logger;
use App\Utils\ResponseBuilder;
use App\Helpers\ContollerHelper;
use App\Handlers\ExampleHandler;
use App\Services\TelegramService;
use App\Services\IdempotencyService;



class ExampleController
{

    public static function executeTaskAction($input)
    {
        try {
            // code
        } catch (\Throwable $error) {
            Logger::error($error->getMessage());
            TelegramService::criticalError($error->getMessage());
            ResponseBuilder::error($error->getMessage());
        }

    }


}