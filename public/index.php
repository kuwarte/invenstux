<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../core/Session.php';
Session::init();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../core/Controller.php';

$db = (new Database())->connect();
$GLOBALS['db'] = $db; 

$router = new Router();
require_once __DIR__ . '/../routes/web.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$router->dispatch($uri, $method, $db);
