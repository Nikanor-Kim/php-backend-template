<?php

namespace App\Router;

use App\Utils\Logger;
use App\Utils\ResponseBuilder;
use App\Utils\DebugHelper;
use App\Utils\RequestValidator;
use App\Controllers\ExampleController;

class Router
{
    public function dispatch(string $uri, string $method): void
    {

        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/');
        $uriParts = explode('/', $uri);


        // Ищем индекс элемента 'task-router'
        $startIndex = array_search('task-router', $uriParts);
        if ($startIndex !== false) {
            // Берём все части начиная с 'task-router'
            $uriParts = array_slice($uriParts, $startIndex);
        } else {
            Logger::warning('task-router not found in URI parts', 'router');
            ResponseBuilder::error("Неизвестный роут", 404);
        }
        // Logger::debug($uriParts, 'uriParts');


        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $isDebug = (php_sapi_name() === 'cli') || ($_GET['debug'] ?? false) || $_ENV['DEBUG'];

        if ($isDebug && $method == 'GET') {
            $input = DebugHelper::load();
        }
        if ($isDebug && $method == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
            DebugHelper::save($input);
        }
        if ($uriParts[1] === 'task') {
            RequestValidator::validateInput($input);

            switch ($uriParts[2] ?? '') {
                case 'add':
                    // TaskController::addToQueue($input);
                    ExampleController::executeTaskAction($input, 'addToQueue');
                    return;

                case 'change-priority':
                    // TaskController::changePriority($input);
                    ExampleController::executeTaskAction($input, 'changePriority');
                    return;

                case 'reassign':
                    // TaskController::reassign($input);
                    ExampleController::executeTaskAction($input, 'reassign');
                    return;

                case 'move-to-request':
                    // TaskController::moveToRequest($input);
                    ExampleController::executeTaskAction($input, 'moveToRequest');
                    return;

                case 'complete':
                    // TaskController::complete($input);
                    ExampleController::executeTaskAction($input, 'complete');
                    return;

                case 'close':
                    // TaskController::close($input);
                    ExampleController::executeTaskAction($input, 'close');
                    return;

                case 'assign-from-queue':
                    // TaskController::assignFromQueue($input);
                    ExampleController::executeTaskAction($input, 'assignFromQueue');
                    return;
            }
        }
        
       

        Logger::error($uri, 'Неизвестный роут');
        ResponseBuilder::error("Неизвестный роут", 404);
    }
}