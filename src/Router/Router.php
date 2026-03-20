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


        // Ищем индекс элемента 'some-route'
        $startIndex = array_search('some-route', $uriParts);
        if ($startIndex !== false) {
            // Берём все части начиная с 'some-route'
            $uriParts = array_slice($uriParts, $startIndex);
        } else {
            Logger::warning('some-route not found in URI parts', 'router');
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
            ExampleController::executeTaskAction($input);
            
        }
        
       

        Logger::error($uri, 'Неизвестный роут');
        ResponseBuilder::error("Неизвестный роут");
    }
}