<?php
declare(strict_types=1);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Router\Router;
use App\Utils\Logger;
use App\Utils\ResponseBuilder;


date_default_timezone_set('Asia/Novosibirsk');

// Заголовки по умолчанию
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (!$_ENV['DEBUG']) {
    header("Content-Type: application/json; charset=utf-8");
}

// Основная работа приложения
try {
    // throw new Exception("test");
    $requestUri = $_SERVER['REQUEST_URI'];
    $requestMethod = $_SERVER['REQUEST_METHOD'];

    $router = new Router();
    $router->dispatch($requestUri, $requestMethod);
} catch (Throwable $e) {
    Logger::error($e->getMessage());
    ResponseBuilder::error($e->getMessage());
}